<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorPayment\VendorPayment;
use App\Bill\Bill;
use App\Vendor;
use DB;
use Auth;
use App\VendorPayment\VendorPaymentLog;
use App\VendorPayment\Item;
use App\PaymentOptionsItem;
use App\PaymentOptions;
use App\Services\JournalService;


class VendorPaymentController extends Controller
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => VendorPayment::with(['vendor', 'currency'])->search()
            ]);
        }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_vendorpayments_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);

        $form = [
            'vendor_id' => null,
            'vendor' => null,
            'number' => counter()->next('vendor_payment'),
            'payment_reference' => null,
            'payment_date' => date('Y-m-d'),
            'payment_mode' => 'cash',
            'amount_paid' => 0,
            'amount_paid_lbp'=> 0,
            'items' => []
        ];

        if($request->has('vendor_id')) {
            $vendor = Vendor::with(['currency'])->findOrFail($request->vendor_id);

            array_set($form, 'vendor_id', $vendor->id);
            array_set($form, 'vendor', $vendor);
            array_set($form, 'currency_id', $vendor->currency->id);
            array_set($form, 'currency', $vendor->currency);

            // get all draft and partialy paid bills
            $bills = $vendor->bills()->whereIn('status_id', [1, 2, 5,6])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as bill_id',
                    DB::raw('0 as amount_applied'),
                    DB::raw('0 as amount_applied_lbp'),
                    DB::raw('1 as amount_applied_lbp_rate'),
                    DB::raw('1 as amount_applied_vat_rate'),
                    DB::raw('0 as amount_applied_vat')
                ]);

            if($bills->count()) {
                array_set($form, 'items', $bills->toArray());
            }
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
        if ($user->is_vendorpayments_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'payment_mode' => 'required',
            'payment_reference' => 'nullable',
            'document' => 'required_if:payment_mode,cheque|image|max:2048',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_paid' => 'nullable',
            'amount_paid_lbp'=> 'nullable',
            'items' => 'required|array|min:1',
            'items.*.bill_id' => 'required|integer',
            // 'items.*.amount_applied' => ['required', 'numeric', 'min:0', 'bill_balance:items.*.bill_id']
            'items.*.amount_applied' => ['nullable'],
            'items.*.amount_applied_lbp' => ['nullable'],
            'items.*.amount_applied_lbp_rate' => ['nullable'],
        
        ]);

        $model = new VendorPayment();
        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        $model->user_id = auth()->id();
        $model->status_id = VendorPayment::PAID;

        $items = collect($request->items)->map(function($item) {
            $total_amount_applied = (round($item['amount_applied'] + ( $item['amount_applied_lbp'] * $item['amount_applied_lbp_rate']),2));
            
            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP - ($item['amount_applied_lbp']) ]);

            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                ->update(['balance' => $balanceUSD - ($item['amount_applied']) ]);

            if( $total_amount_applied > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        // throw error if amount_applied is invalid

        if($items->sum('amount_applied') != $request->amount_paid) {
            return api([
                'errors' => [
                    'amount_paid' => ['Amount paid does not match amount applied']
                ]
            ], 422);
        }

        // $lbpSum = $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate');
      
        // $LbpVat = $lbpSum;
        // if(round($LbpVat,2) != $request->amount_paid_lbp ) {
        //     return api([
        //         'errors' => [
        //             'amount_paid_lbp' => ['Amount paid does not match amount applied try to receive one bill in case you have two payments in second currency'],
        //         ]
        //     ], 422);
        // }

         $lbpSum = $items->sum(function($item) {
                return $item['amount_applied_lbp'] / $item['amount_applied_lbp_rate'];
         });
      $vatSum = $items->sum(function($item) {
                return $item['amount_applied_vat'] / $item['amount_applied_vat_rate'];
            });

        // throw new \Exception ($lbpSum);
        // $lbpSum = $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate');
        // $vatSum = $items->sum('amount_applied_vat') / $items->sum('amount_applied_vat_rate');
        $LbpVat = $lbpSum  + $vatSum;
        if(round($LbpVat,2) != $request->amount_paid_lbp ) {
            return api([
                'errors' => [
                    'amount_paid_lbp' => [round($LbpVat,2) . ' '.$request->amount_paid_lbp . 'Amount paid  does not match amount applied try to receive one invoice in case you have two payments in '],
                ]
            ], 422);
        }


        // $model->amount_paid = (round($items->sum('amount_applied') + ( $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate')),2));
        // $model->amount_paid_usd = (round($items->sum('amount_applied'),2));

         
        $model->amount_paid = (round($items->sum('amount_applied') + $lbpSum + $vatSum,2));
        $model->amount_paid_usd = (round($items->sum('amount_applied'),2));
        $model->amount_paid_lbp = (round($lbpSum,2));
        $model->amount_paid_lbprate = (round($vatSum,2));



        $model = DB::transaction(function() use ($model, $items) {

            $model->number = counter()->next('vendor_payment');

            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update bills

            $model->items->each(function($item) {
                $bill = $item->bill;
                $amount = $bill->amount_paid + (round($item->amount_applied + ($item->amount_applied_lbp / $item->amount_applied_lbp_rate) + ($item->amount_applied_vat / $item->amount_applied_vat_rate),2));
                // $amount = $bill->amount_paid + $item->amount_applied;

                if($amount > $bill->total) {
                    throw new Exception('Amount overflow');
                }

                $bill->amount_paid = $amount;
                $bill->status_id = Bill::PARTIALLY_PAID;

                if($bill->amount_paid == $bill->total) {
                    $bill->status_id = Bill::PAID;
                }

                $bill->save();
            });

            //  2. update vendor total_expense
            $vendor = $model->vendor;
            $vendor->total_expense = $vendor->total_expense + $model->amount_paid;
            $vendor->save();

            counter()->increment('vendor_payment');

            return $model;
        });

           $vendor_payment_log = new VendorPaymentLog();
        $vendor_payment_log->comment = "Store";
        $vendor_payment_log->body = \App\VendorPayment\VendorPayment::where('id','=',$model->id)->get();
        $vendor_payment_log->items = \App\VendorPayment\Item::where('vendor_payment_id','=',$model->id)->get();
        $vendor_payment_log->save();
        

        $payment_option_balance = PaymentOptions::where('id','=',$request->payment_option_id)->value('balance');
        DB::table('payment_options')
        ->where('id', $request->payment_option_id)
        ->update(['balance' => $payment_option_balance - $model->amount_paid]);
        $payment_items = new PaymentOptionsItem;
        $payment_items->payment_options_id = $request->payment_option_id;
        $payment_items->payment = $model->amount_paid;
        $payment_items->user_id = auth()->id();
        $payment_items->created_by = $username;
        $payment_items->date = date('Y-m-d');
        $payment_items->time = now();
        $payment_items->year_date = date('Y');
        $payment_items->document = 'vendor_payment';
        $payment_items->document_id = $model->id;
        $payment_items->document_number = $model->number;
        $payment_items->client_id = $model->vendor_id;
        $payment_items->client_name =  Vendor::where('id', $model->vendor_id)->value('company');
        $payment_items->save();


                   $documentData = $model->toArray();
                $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'vendor_payment');
                if (!$journalVoucher) {
                    throw new \Exception ("vendor_payment saved but failed to create journal entries");
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => VendorPayment::with(['items.bill', 'vendor', 'currency'])->findOrFail($id)
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = VendorPayment::with(['items.bill', 'vendor', 'currency'])->findOrFail($id);
            return pdf('docs.vendor_payment', $data);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_clientpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
                $model = VendorPayment::findOrFail($id);
                $modelItem = Item::where('id','=',$id)->get();
                foreach ($modelItem as $modelItemx)
                {
                    
                    $CPAmount = $modelItemx->amount_applied;
                    $test1 = Bill::where('id','=',$modelItemx->bill_id)->sum('amount_paid');
                    Bill::where('id','=',$modelItemx->bill_id)->update(['amount_paid' => $test1 - $CPAmount]);
                    Bill::where('id','=',$modelItemx->bill_id)->update(['status_id' => '1']);
                }
        
                $modelVendor =VendorPayment::where('id','=',$id)->get();
                foreach ($modelVendor as $vendorXX)
                {
                    $CPAmount2 = $vendorXX->amount_paid;
                    $test2 = Vendor::where('id','=',$vendorXX->vendor_id)->sum('total_expense');
                    Vendor::where('id','=',$vendorXX->vendor_id)->update(['total_expense' => $test2 - $CPAmount2]);
                }
      

                $vendor_payment_log = new VendorPaymentLog();
                $vendor_payment_log->comment = "Deleted";
                $vendor_payment_log->body = \App\VendorPayment\VendorPayment::where('id','=',$model->id)->get();
                $vendor_payment_log->items = \App\VendorPayment\Item::where('vendor_payment_id','=',$model->id)->get();
                $vendor_payment_log->save();
        

                $model->items()->delete();
                $model->delete();
            // $modelItem->delete();

            return api([
                'deleted' => true
            ]);
        }
}


}
