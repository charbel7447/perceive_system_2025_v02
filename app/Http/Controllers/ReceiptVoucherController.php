<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReceiptVoucher\ReceiptVoucher;
use App\ReceiptVoucher\Item;
use App\Client;

use App\ExchangeRate\ExchangeRate;

use Exception;
use DB;
use Auth;
use Carbon\Carbon;
use App\Services\JournalService;
use App\PaymentOptionsItem;
use App\PaymentOptions;
use App\Invoice\Invoice;

class ReceiptVoucherController extends Controller
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
        if ($user->is_clientpayments_view == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => ReceiptVoucher::with(['client', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }

        public function showInvoices($id)
    {
        $data = ReceiptVoucher::with(['client', 'currency'])
            // ->whereStatusId(ReceiptVoucher::RECEIVED)
            ->where('status_id','!=', 3)
            ->findOrFail($id);
        $data->items = $data->client->invoices()->whereIn('status_id', [2, 3])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as invoice_id',
                    DB::raw('0 as amount_applied'),
                    DB::raw('0 as amount_applied_usd')
                ]);
        return api([
            'data' => $data
        ]);
    }

    public function applyInvoices($id, Request $request)
    {
        $model = ReceiptVoucher::where('status_id','!=', 3)
        // whereStatusId(ReceiptVoucher::RECEIVED)
            ->findOrFail($id);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.invoice_id' => 'required|integer',
            // 'items.*.amount_applied_usd' => ['required', 'numeric', 'min:0',  'invoice_balance:items.*.invoice_id']
        ]);

        $items = collect($request->items)->map(function($item) {
              
            
            if($item['amount_applied_usd'] > $item['runningBalance']) {

                throw new \Exception('Amount Applied on Invoice Greater then Invoice Balance');
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
                $model->status_id = ReceiptVoucher::APPLIED;
            }else{
                $model->status_id = ReceiptVoucher::PARTIALLY_APPLIED;
            }
            
            $model->storeHasMany([
                'items' => $request->items
            ]);

            //  1. update invoices

            // $model->items->each(function($item) {
             $items = collect($request->items)->map(function ($item) use ($model) {
                // throw new \Exception($item['invoice_id']);
                $RVInvoice = new \App\ReceiptVoucher\Invoices();
                $RVInvoice->receipt_voucher_id = $model->id;
                $RVInvoice->invoice_id = $item['invoice_id'];
                $RVInvoice->number = $item['number'];
                $RVInvoice->date = $item['date'];
                $RVInvoice->currency_id = $item['currency_id'];
                $RVInvoice->total = $item['total'];
                $RVInvoice->runningBalance = $item['runningBalance'];
                $RVInvoice->amount_applied = $item['amount_applied'];
                $RVInvoice->amount_applied_usd = $item['amount_applied_usd'];
                // $RVInvoice->status_id = $item['status_id'];
                // $RVInvoice->save();

                $invoice = Invoice::findorfail($item['invoice_id']);
                // throw new \Exception($invoice->currency_id);
                if($invoice->currency_id == 1){
                    $amount = $invoice->amount_paid + $item['amount_applied_usd'];
                }else{
                    $amount = $invoice->amount_paid + $item['amount_applied'];
                }
               

                if($amount > $invoice->total) {
                    throw new Exception('Amount overflow');
                }

                $invoice->amount_paid = $amount;
                $invoice->status_id = Invoice::PARTIALLY_PAID;

                $invoice_status = Invoice::PARTIALLY_PAID;

                if($invoice->amount_paid == $invoice->total) {
                    $invoice->status_id = Invoice::PAID;
                     $invoice_status = Invoice::PARTIALLY_PAID;
                }
                $RVInvoice->status_id = $invoice_status;
                $RVInvoice->save();
                $invoice->save();
            });

            //  2. update client revenue and also reduce unused credit
            $client = $model->client;
            $total = $model->amount_applied_usd;
            $client->total_revenue = $client->total_revenue + $total;
            $client->unused_credit = $client->unused_credit - $total;
            $client->save();

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function MinishowInvoices($id)
    {
        $rv = ReceiptVoucher::findOrFail($id);

        $model = $rv->invoices()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }
    

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_clientpayments_create == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $request->validate([
            // 'client_id' => 'sometimes|required|integer|exists:clients,id'
        ]);

        $global_vat_percentage = \App\Settings::where('key','=','global_vat_percentage')->value('value');
        $exchange_rate = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $form = [
            'client_id' => null,
            'client' => null,
            'number' => counter()->next('receipt_voucher'),
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

        if($request->has('client_id')) {
            $client = Client::with(['currency'])->findOrFail($request->client_id);
            $global_vat_percentage = \App\Settings::where('key','=','global_vat_percentage')->value('value');
            $exchange_rate = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            array_set($form, 'client_id', $client->id);
            array_set($form, 'client', $client);
            array_set($form, 'currency_id', $client->currency->id);
            array_set($form, 'currency_code', $client->currency->code);
            array_set($form, 'currency', $client->currency);
            array_set($form, 'client_balance', $client->balance);
            array_set($form, 'exchange_rate', $exchange_rate);
            array_set($form, 'global_vat_percentage', $global_vat_percentage);
            array_set($form, 'vat_status', $client->vat_status);
            array_set($form, 'number', counter()->next('receipt_voucher'));
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

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.payment_option_id' => 'required|integer',
            'document' => 'nullable|image|max:2048'
        ]);

        $model = new ReceiptVoucher();
        $model->fill($request->except('items'));

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // $model->user_id = auth()->id();
        $model->status_id = ReceiptVoucher::RECEIVED;
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = $request->client_id;

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

        $tobepaid = DB::table('clients')
        ->where('id', $model->client_id)->value('paid');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['paid' => $tobepaid + $total_debit_usd]);

        $client_balance = DB::table('clients')
        ->where('id', $model->client_id)->value('balance');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['balance' => $client_balance - $total_debit_usd]);

        if($client_balance == $total_debit_usd){
            
        }else{
            DB::table('clients')
            ->where('id', $model->client_id)
            ->update(['last_payment_date' => date('Y-m-d')]);
        }
        
        $items = collect($request->items)->map(function ($item) use ($total_debit_usd) {
            $payment_option_balance = PaymentOptions::where('id', '=', $item['payment_option_id'])->value('balance');

            // throw new \Exception($payment_option_balance. '        ' .$total);

            DB::table('payment_options')
                ->where('id', $item['payment_option_id'])
                ->update(['balance' => $payment_option_balance + $total_debit_usd]);
        });

        $model = DB::transaction(function() use ($model, $request, $total_debit_usd) {

            $model->number = counter()->next('receipt_voucher');

            $model->storeHasMany([
                'items' => $request->items
            ]);

          
            //  2. update client revenue
            $client = $model->client;
            $client->total_revenue = $client->total_revenue + $total_debit_usd  ;
            $client->save();

            counter()->increment('receipt_voucher');

            return $model;
        });


        $documentData = $model->toArray();
        $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'receipt_voucher');
        if (!$journalVoucher) {
            throw new \Exception ("client_payment saved but failed to create journal entries");
        }
                
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_clientpayments_create == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => ReceiptVoucher::with(['items','items.account_receivable_currency','items.payment_options','items.account_receivable', 'client', 'currency'])->findOrFail($id)
        ]);}
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_clientpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $data = ReceiptVoucher::with(['items','items.account_receivable_currency','items.payment_options','items.account_receivable', 'client', 'currency'])->findOrFail($id);
        return pdf('docs.receipt_voucher', $data);
    }}

       public function edit($id, Request $request)
    {

        $form = ReceiptVoucher::with(['items','items.account_receivable_currency','items.payment_options','items.account_receivable', 'client', 'currency'])->findOrFail($id);
        return api([
            'form' => $form
        ]);
    }

    
       public function update($id, Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.payment_option_id' => 'required|integer',
            'document' => 'nullable|image|max:2048'
        ]);

        $model = ReceiptVoucher::findOrFail($id);
        $model->fill($request->except('items'));

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // $model->user_id = auth()->id();
        $model->status_id = ReceiptVoucher::RECEIVED;
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = $request->client_id;

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

        $tobepaid = DB::table('clients')
        ->where('id', $model->client_id)->value('paid');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['paid' => $tobepaid + $total_debit_usd]);

        $client_balance = DB::table('clients')
        ->where('id', $model->client_id)->value('balance');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['balance' => $client_balance - $total_debit_usd]);

        if($client_balance == $total_debit_usd){
            
        }else{
            DB::table('clients')
            ->where('id', $model->client_id)
            ->update(['last_payment_date' => date('Y-m-d')]);
        }
        
        $items = collect($request->items)->map(function ($item) use ($total_debit_usd) {
            $payment_option_balance = PaymentOptions::where('id', '=', $item['payment_option_id'])->value('balance');

            // throw new \Exception($payment_option_balance. '        ' .$total);

            DB::table('payment_options')
                ->where('id', $item['payment_option_id'])
                ->update(['balance' => $payment_option_balance + $total_debit_usd]);
        });

        $model = DB::transaction(function() use ($model, $request, $total_debit_usd) {

            $model->number = counter()->next('receipt_voucher');

            $model->updateHasMany([
                'items' => $request->items
            ]);

          
            //  2. update client revenue
            $client = $model->client;
            $client->total_revenue = $client->total_revenue + $total_debit_usd  ;
            $client->save();

            counter()->increment('receipt_voucher');

            return $model;
        });


        // $documentData = $model->toArray();
        // $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'receipt_voucher');
        // if (!$journalVoucher) {
        //     throw new \Exception ("client_payment saved but failed to create journal entries");
        // }
                
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function destroy($id)
    {
        // throw new \Exception("Delete JV Also");
        $user = auth()->user();
        if ($user->is_clientpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $model = ReceiptVoucher::findOrFail($id);
        if($model->journal_id > 0){
            throw new \Exception("Delete JV First!");
        }
        $modelItem =Item::where('receipt_voucher_id','=',$id)->get();
        foreach ($modelItem as $modelItemx)
        {
            
            $CPAmount = $modelItemx->amount_applied_usd;
            $test1 = Invoice::where('id','=',$modelItemx->invoice_id)->sum('amount_paid');
            Invoice::where('id','=',$modelItemx->invoice_id)->update(['amount_paid' => $test1 - $CPAmount]);
            Invoice::where('id','=',$modelItemx->invoice_id)->update(['status_id' => '1']);
        }
        
        $modelClient =ReceiptVoucher::where('id','=',$id)->get();
        foreach ($modelClient as $ClientItemx)
        {
            
            $CPAmount2 = $ClientItemx->applied_amount;
            $test2 = Client::where('id','=',$ClientItemx->client_id)->sum('total_revenue');
            Client::where('id','=',$ClientItemx->client_id)->update(['total_revenue' => $test2 - $CPAmount2]);
        }
      
        $oldbalance =Client::where('id','=',$model->client_id)->value('balance');
        Client::where('id','=',$model->client_id)->update(['balance' => $oldbalance - $model->amount_received_usd]);
        $model->items()->delete();
        $model->delete();
        // $modelItem->delete();
        // $modelItem->delete();

        return api([
            'deleted' => true
        ]);
    }}
}