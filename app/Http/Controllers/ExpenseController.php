<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense\Expense;
use App\Vendor;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\Services\JournalService;

class ExpenseController extends Controller
{
          // Declare the property
    protected $journalService;

    // Inject JournalService in constructor
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Expense::with(['vendor', 'currency'])->search()
            ]);
        }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_vendorexpenses_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $form = [
            'vendor_id' => null,
            'vendor' => null,
            'number' => counter()->next('expense'),
            'payment_date' => date('Y-m-d'),
            'amount_paid' => 0,
            'amount_paid_lbp' => 0,
            'description' => null,
            'exchangerate'=> $exchange,
            'items' => [
                [
                    'description' => null,
                    'debit' => 0,
                    'debit_vat' => 0,
                    'date'=> date('Y-m-d')
                ]
                ],
            'items2' => [
                [
                    'description' => null,
                    'debit' => 0,
                    'debit_vat' => 0,
                    'date'=> date('Y-m-d')
                ]
            ]
        ];

          if($request->has('vendor_id')) {
            $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            $vendor = Vendor::with(['currency'])->findOrFail($request->vendor_id);

            array_set($form, 'vendor_id', $vendor->id);
            array_set($form, 'vat_status', $vendor->vat_status);
            array_set($form, 'vendor', $vendor);
            array_set($form, 'currency_id', $vendor->currency->id);
            array_set($form, 'currency', $vendor->currency);
            array_set($form, 'exchangerate', $exchange);

            if($request->has('bill_id')) {
               
                $bill = \App\Bill\Bill::with(['currency'])->findOrFail($request->bill_id);

                //  throw new \Exception ($bill);
                array_set($form, 'bill', $bill);
                array_set($form, 'bill_id', $bill->id);
                array_set($form, 'bill_number', $bill->number);
                array_set($form, 'bill_date', $bill->date);
                array_set($form, 'bill_total', $bill->total);
            }
        
             $items = [
                [
                    'description' => null,
                    'debit' => 0,
                    'debit_vat' => 0,
                    'date'=> date('Y-m-d')
                    ]];
            $items2 = [
                [
                    'description' => null,
                    'debit' => 0,
                    'debit_vat' => 0,
                    'date'=> date('Y-m-d')
                    ]];
            array_set($form, 'items', $items);
            array_set($form, 'items2', $items2);
        } else {
            $form = array_merge($form, currency()->defaultToArray());
        }

        return api([
            'form' => $form
        ]);
    }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_vendorexpenses_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_paid' => 'nullable',
            'amount_paid_lbp' => 'nullable',
            'description' => 'nullable|max:2000',
            'document' => 'nullable|image|max:2048'
        ]);

        $model = new Expense();
        $model->fill($request->except('document','items','items2'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = auth()->id();

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        $items = collect($request->items);
        $items2 = collect($request->items2);
        // throw new \Exception ($request->exchangerate);
        $exchangerate = $request->exchangerate;
        
        $total_usd_1 = $items->sum(function($item) {
            // throw new \Exception ($item['account_receivable_currency_id']); 
            if($item['account_receivable_currency_id'] == 1){
                return $item['debit'];
            }
        });
        $total_lbp_1 = $items->sum(function($item) {
            if($item['account_receivable_currency_id'] == 2){
                return $item['debit'];
            }
        });
        $total_vat_1 = $items->sum(function($item) use ($exchangerate) {
            if ($item['account_receivable_currency_id'] == 1) {
                return $item['debit_vat'];
            }
            if ($item['account_receivable_currency_id'] == 2) {
                // throw new \Exception($exchangerate);
                return ($item['debit_vat'] / $exchangerate);
            }
        });

         $total_usd_2 = $items2->sum(function($item) {
            if($item['account_payable_currency_id'] == 1){
                return $item['debit'];
            }
        });
        $total_lbp_2 = $items2->sum(function($item) {
            if($item['account_payable_currency_id'] == 2){
                return $item['debit'];
            }
        });
        $total_vat_2 = $items2->sum(function($item) use ($exchangerate) {
            if ($item['account_payable_currency_id'] == 1) {
                return $item['debit_vat'];
            }
            if ($item['account_payable_currency_id'] == 2) {
                // throw new \Exception($exchangerate);
                return ($item['debit_vat'] / $exchangerate);
            }
        });
        
     
        $model->total_usd_1 = $total_usd_1;
        $model->total_lbp_1 = $total_lbp_1;
        $model->total_vat_1 = $total_vat_1;
        $model->total_usd_2 = $total_usd_2;
        $model->total_lbp_2 = $total_lbp_2;
        $model->total_vat_2 = $total_vat_2;
        $total_expenses = $total_usd_1 + ($total_lbp_1/$request->exchangerate) + $total_vat_1;
        $model->total_debit = $total_expenses;
        $model->total_credit = $total_usd_2 + $total_lbp_2 + $total_vat_2;

        if($total_expenses != ($total_usd_2 + $total_lbp_2 + $total_vat_2)){
            throw new \Exception("Debit != Credit..");
        }
        $model->total = $total_expenses;
        // throw new \Exception($total_expenses);
        //adjust the bill items landed price
        $bill_items = \App\Bill\Item::where('bill_id','=',$request->bill_id)->get();
        $bill_total = \App\Bill\Bill::where('id','=',$request->bill_id)->value('subtotal');
        // throw new \Exception($bill_items);
        $total_total_expenses = 0;
        $total_item_price = 0;
        foreach($bill_items as $item_bill){
            $total_item_price = $item_bill->unit_price * $item_bill->qty;
            $percentage = ($total_item_price / $bill_total ) * 100;
            $additonal_expenses = $total_expenses * ($percentage / 100);
            $total_item_after_additional = $additonal_expenses + $total_item_price;
            $total_total_expenses += $additonal_expenses;
            $item_cost = $total_item_after_additional / $item_bill->qty;
            \App\Bill\Item::where('id','=',$item_bill->id)->update([
                'percentage_total' => $percentage,
                'additional_expenses' => $additonal_expenses,
                'final_cost_price' => $item_cost,
            ]);

            
            $product_id = $item_bill->product_id;
            $product = \App\Product\Product::findorFail($product_id);
            $old_stock = $product->current_stock * $product->sale_price;
            $new_stock = $item_bill->qty * $item_cost;
            if($product->current_stock > 0){
                $new_avg_cost = ($old_stock + $new_stock) / ($product->current_stock + $item_bill->qty);
            }else{
                $new_avg_cost = ($old_stock + $new_stock) / ( $item_bill->qty);
            }
            
            \App\Product\Product::where('id','=',$product->id)->update([
                'sale_price' => $new_avg_cost,
                'purchase_price' => $new_avg_cost
            ]);
            // throw new \Exception($product->sale_price. '   '.$product->purchase_price. '  '.$product->current_stock);

        }
      
        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('expense');

            // $model->save();
            $model->storeHasMany([
                'items' => $request->items,
                'items2' => $request->items2,
            ]);


            // update vendor total_expense
            $vendor = $model->vendor;
            $vendor->total_expense = $vendor->total_expense + $model->amount_paid;
            $vendor->save();

            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP - $model->amount_paid_lbp ]);

            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                ->update(['balance' => $balanceUSD - $model->amount_paid ]);

            counter()->increment('expense');

            return $model;
        });

               $vendor_id = $model->vendor_id;
                $old_balance = \App\Vendor::where('id','=',$vendor_id)->value('balance');
                \App\Vendor::where('id','=',$vendor_id)->update(['balance' => $old_balance + $model->total]);
                

                   $documentData = $model->toArray();
                $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'expenses');
                if (!$journalVoucher) {
                    throw new \Exception ("expenses saved but failed to create journal entries");
                }

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Expense::with(['vendor', 'currency','bill','items','items2'])->findOrFail($id)
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = Expense::with(['vendor', 'currency'])->findOrFail($id);
            return pdf('docs.expense', $data);
        }
    }

      public function destroy($id)
    {
        $user = auth()->user();
            $model = Expense::findOrFail($id);

                   $vendor_id = $model->vendor_id;
                $old_balance = \App\Vendor::where('id','=',$vendor_id)->value('balance');
                \App\Vendor::where('id','=',$vendor_id)->update(['balance' => $old_balance - $model->total]);
                

            $voucher = \App\JournalVoucher\JournalVoucher::findorfail($model->journal_id);
            $voucher_mov = new \App\JournalVoucher\JournalVoucherMovement;
            $voucher_mov->journal_voucher_id  = $model->journal_id;
            $voucher_mov->number  = $voucher->number;
            $voucher_mov->currency_id  = $voucher->currency_id;
            $voucher_mov->type  = 'Automatically Deleted by Expenses #'.$model->number;
            $voucher_mov->movement_date  = now();
            $voucher_mov->items  = json_encode($voucher->items());
            $voucher_mov->save();
            \App\JournalVoucher\JournalVoucher::where('id','=',$model->journal_id)->delete();
            \App\JournalVoucher\Item::where('journal_voucher_id','=',$model->journal_id)->delete();
            $model->items()->delete();
            $model->items2()->delete();
            $model->delete();
            return api([
                'deleted' => true
            ]);
    }
}
