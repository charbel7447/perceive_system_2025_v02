<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentVoucher\PaymentVoucher;
use App\PaymentVoucher\Item;
use App\Vendor;

use App\ExchangeRate\ExchangeRate;

use Exception;
use DB;
use Auth;
use Carbon\Carbon;
use App\Services\JournalService;
use App\PaymentOptionsItem;
use App\PaymentOptions;
use App\Bill\Bill;

class PaymentVoucherController extends Controller
{
     // Declare the property
    protected $journalService;

    // Inject JournalService in constructor
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $user = auth()->user();
        if ($user->is_vendorpayments_view == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => PaymentVoucher::with(['vendor', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }


        public function showInvoices($id)
    {
        $data = PaymentVoucher::with(['vendor', 'currency'])
            // ->whereStatusId(PaymentVoucher::RECEIVED)
            ->where('status_id','!=', 3)
            ->findOrFail($id);
        $data->items = $data->vendor->bills()->whereIn('status_id', [2, 5,6])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as bill_id',
                    DB::raw('0 as amount_applied'),
                    DB::raw('0 as amount_applied_usd')
                ]);
        return api([
            'data' => $data
        ]);
    }


        public function applyInvoices($id, Request $request)
    {
        $model = PaymentVoucher::where('status_id','!=', 3)
        // whereStatusId(PaymentVoucher::RECEIVED)
            ->findOrFail($id);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.bill_id' => 'required|integer',
            // 'items.*.amount_applied_usd' => ['required', 'numeric', 'min:0',  'invoice_balance:items.*.invoice_id']
        ]);

        $items = collect($request->items)->map(function($item) {
              
            
            if($item['amount_applied_usd'] > $item['runningBalance']) {

                throw new \Exception('Amount Applied on Bill Greater then Bill Balance');
            }

            if($item['amount_applied_usd'] > 0) {
                return $item;
            }

          

        })->reject(function($item) {
            return is_null($item);
        });


        // throw new \Exception ($items->sum('amount_applied_usd'));
        // throw error if amount_applied is invalid or less
        if($items->sum('amount_applied_usd') > $model->balance_amount) {
            return api([
                'errors' => [
                    'balance_amount' => ['Amount received greater than receipt amount']
                ]
            ], 422);
        }

       
        $model->applied_amount = $items->sum('amount_applied_usd');
        $amount_applied_usd = $items->sum('amount_applied_usd');
        $rv_balance_amount = $request->balance_amount;

        $model->applied_date = date('Y-m-d');

        $username = Auth::user()->name;
        $model ->created_by = $username;
        
        $model->balance_amount = $rv_balance_amount - $amount_applied_usd;
        $model = DB::transaction(function() use ($model, $items, $amount_applied_usd,$rv_balance_amount, $request) {

            if($amount_applied_usd == $rv_balance_amount){
                $model->status_id = PaymentVoucher::APPLIED;
            }else{
                $model->status_id = PaymentVoucher::PARTIALLY_APPLIED;
            }
            
            $model->storeHasMany([
                'items' => $request->items
            ]);

            //  1. update invoices

            // $model->items->each(function($item) {
             $items = collect($request->items)->map(function ($item) use ($model) {
                // throw new \Exception($item['invoice_id']);
                $RVInvoice = new \App\PaymentVoucher\Bills();
                $RVInvoice->payment_voucher_id = $model->id;
                $RVInvoice->bill_id = $item['bill_id'];
                $RVInvoice->number = $item['number'];
                $RVInvoice->date = $item['date'];
                $RVInvoice->currency_id = $item['currency_id'];
                $RVInvoice->total = $item['total'];
                $RVInvoice->runningBalance = $item['runningBalance'];
                $RVInvoice->amount_applied = $item['amount_applied'];
                $RVInvoice->amount_applied_usd = $item['amount_applied_usd'];
                // $RVInvoice->status_id = $item['status_id'];
                // $RVInvoice->save();

                $bill = Bill::findorfail($item['bill_id']);
                // throw new \Exception($bill->currency_id);
                if($bill->currency_id == 1){
                    $amount = $bill->amount_paid + $item['amount_applied_usd'];
                }else{
                    $amount = $bill->amount_paid + $item['amount_applied'];
                }
               

                if($amount > $bill->total) {
                    throw new Exception('Amount overflow');
                }

                $bill->amount_paid = $amount;
                $bill->status_id = Bill::PARTIALLY_PAID;

                $bill_status = Bill::PARTIALLY_PAID;

                if($bill->amount_paid == $bill->total) {
                    $bill->status_id = Bill::PAID;
                     $bill_status = Bill::PARTIALLY_PAID;
                }
                $RVInvoice->status_id = $bill_status;
                $RVInvoice->save();
                $bill->save();
            });

            //  2. update client revenue and also reduce unused credit
            $vendor = $model->vendor;
            $total = $model->amount_applied_usd;
            $vendor->total_expense = $vendor->total_expense + $total;
            $vendor->save();

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function MinishowInvoices($id)
    {
        $rv = PaymentVoucher::findOrFail($id);

        $model = $rv->bills()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
         $user = auth()->user();
        if ($user->is_vendorpayments_create == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $request->validate([
            // 'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);

        $global_vat_percentage = \App\Settings::where('key','=','global_vat_percentage')->value('value');
        $exchange_rate = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $form = [
            'vendor_id' => null,
            'vendor' => null,
            'number' => counter()->next('payment_voucher'),
            'reference' => null,
            'date' => date('Y-m-d'),
            // 'payment_mode' => 'none',
            'amount_received' => 0,
            'vat_status' => 0,
            'amount_received_lbp' => 0,
            'exchange_rate' => $exchange_rate,
            'global_vat_percentage' => $global_vat_percentage,
            'items' => [
                [
                    'description' => 0,
                    'debit_vat' => 0,
                ]
            ]
        ];

        if($request->has('vendor_id')) {
            $vendor = Vendor::with(['currency'])->findOrFail($request->vendor_id);
            $global_vat_percentage = \App\Settings::where('key','=','global_vat_percentage')->value('value');
            $exchange_rate = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            array_set($form, 'vendor_id', $vendor->id);
            array_set($form, 'vendor', $vendor);
            array_set($form, 'currency_id', $vendor->currency->id);
            array_set($form, 'currency_code', $vendor->currency->code);
            array_set($form, 'currency', $vendor->currency);
            array_set($form, 'vendor_balance', $vendor->balance);
            array_set($form, 'exchange_rate', $exchange_rate);
            array_set($form, 'global_vat_percentage', $global_vat_percentage);
            array_set($form, 'vat_status', $vendor->vat_status);
            array_set($form, 'number', counter()->next('payment_voucher'));
            array_set($form, 'items', [
                [ 
                    'description' => 0,
                    'debit' => 0,
                    'debit_usd' => 0,
                    'debit_vat' => 0,
                    'date' => Date('Y-m-d')
                ]
            ]);

        } else {
            $form = array_merge($form, currency()->defaultToArray());
        }

        return api([
            'form' => $form
        ]);}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'vendor_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.payment_option_id' => 'required|integer',
            'document' => 'nullable|image|max:2048'
        ]);

        $model = new PaymentVoucher();
        $model->fill($request->except('items'));

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // $model->user_id = auth()->id();
        $model->status_id = PaymentVoucher::RECEIVED;
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = $request->vendor_id;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;

        $items = collect($request->items);
        $total = $items->sum(function($item) {
                return $item['debit'] + $item['debit_vat'];
        });

        $total_debit = $items->sum(function($item) {
                return $item['debit'];
        });

        $total_debit_usd = $items->sum(function($item) {
                return $item['debit_usd'];
        });

        $total_debit_vat = $items->sum(function($item) {
                return $item['debit_vat'];
        });

        
        $balance_amount = $items->sum(function($item) {
                return $item['debit_usd'];
        });

        $model->total = $total;
        $model->total_debit = $total_debit;
        $model->total_debit_usd = $total_debit_usd;
        $model->total_debit_vat = $total_debit_vat;
        $model->balance_amount = $balance_amount;

        $tobepaid = DB::table('vendors')
        ->where('id', $model->vendor_id)->value('paid');

        DB::table('vendors')
        ->where('id', $model->vendor_id)
        ->update(['paid' => $tobepaid + $total_debit_usd]);

        $vendor_balance = DB::table('vendors')
        ->where('id', $model->vendor_id)->value('balance');

        DB::table('vendors')
        ->where('id', $model->vendor_id)
        ->update(['balance' => $vendor_balance - $total_debit_usd]);

        if($vendor_balance == $total_debit_usd){
            
        }else{
            DB::table('vendors')
            ->where('id', $model->vendor_id)
            ->update(['last_payment_date' => date('Y-m-d')]);
        }
        
        $items = collect($request->items)->map(function ($item) use ($total_debit_usd) {
            $payment_option_balance = PaymentOptions::where('id', '=', $item['payment_option_id'])->value('balance');

            // throw new \Exception($payment_option_balance. '        ' .$total);

            DB::table('payment_options')
                ->where('id', $item['payment_option_id'])
                ->update(['balance' => $payment_option_balance - $total_debit_usd]);
        });

        $model = DB::transaction(function() use ($model, $request, $total_debit_usd) {

            $model->number = counter()->next('payment_voucher');

            $model->storeHasMany([
                'items' => $request->items
            ]);

          
             //  2. update vendor total_expense
            $vendor = $model->vendor;
            $vendor->total_expense = $vendor->total_expense + $total_debit_usd;
            $vendor->save();

            counter()->increment('payment_voucher');

            return $model;
        });


        $documentData = $model->toArray();
        $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'payment_voucher');
        if (!$journalVoucher) {
            throw new \Exception ("vendor_payment saved but failed to create journal entries");
        }
                
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
       public function show($id)
    {
        $user = auth()->user();
        if ($user->is_vendorpayments_create == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => PaymentVoucher::with(['items','items.account_receivable_currency','items.payment_options','items.account_receivable', 'vendor', 'currency'])->findOrFail($id)
        ]);}
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_vendorpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $data = PaymentVoucher::with(['items','items.account_receivable_currency','items.payment_options','items.account_receivable', 'vendor', 'currency'])->findOrFail($id);
        return pdf('docs.payment_voucher', $data);
    }}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // throw new \Exception("Delete JV Also");
        $user = auth()->user();
        if ($user->is_vendorpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $model = PaymentVoucher::findOrFail($id);
        if($model->journal_id > 0){
            throw new \Exception("Delete JV First!");
        }
        $modelItem =Item::where('payment_voucher_id','=',$id)->get();
        foreach ($modelItem as $modelItemx)
        {
            
            $CPAmount = $modelItemx->amount_applied_usd;
            $test1 = Bill::where('id','=',$modelItemx->bill_id)->sum('amount_paid');
            Bill::where('id','=',$modelItemx->bill_id)->update(['amount_paid' => $test1 - $CPAmount]);
            Bill::where('id','=',$modelItemx->bill_id)->update(['status_id' => '1']);
        }
        
        $modelClient =PaymentVoucher::where('id','=',$id)->get();
        foreach ($modelClient as $ClientItemx)
        {
            
            $CPAmount2 = $ClientItemx->applied_amount;
            $test2 = Vendor::where('id','=',$ClientItemx->vendor_id)->sum('total_revenue');
            Vendor::where('id','=',$ClientItemx->vendor_id)->update(['total_revenue' => $test2 - $CPAmount2]);
        }
      
        $oldbalance =Vendor::where('id','=',$model->vendor_id)->value('balance');
        Vendor::where('id','=',$model->vendor_id)->update(['balance' => $oldbalance - $model->amount_received_usd]);
        $model->items()->delete();
        $model->delete();
        // $modelItem->delete();
        // $modelItem->delete();

        return api([
            'deleted' => true
        ]);
    }}
}
