<?php

namespace App\Http\Controllers;

use App\AccountItems;
use Illuminate\Http\Request;
use App\ClientPayment\ClientPayment;
use App\Rules\InvoiceBalance;
use App\Invoice\Invoice;
use App\Client;
use App\ClientPayment\Item;
use Exception;
use DB;
use Auth;
use Carbon\Carbon;
use App\ClientPayment\ClientPaymentLog;
use App\PaymentOptionsItem;
use App\PaymentOptions;
use App\Services\JournalService;

class ClientPaymentController extends Controller
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
            'data' => ClientPayment::with(['client', 'currency'])->orderby('created_at','desc')->search()
        ]);}
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

        $form = [
            'client_id' => null,
            'client' => null,
            'number' => counter()->next('client_payment'),
            'payment_reference' => null,
            'payment_date' => date('Y-m-d'),
            // 'payment_mode' => 'none',
            'amount_received' => 0,
            'amount_received_lbp' => 0,
            'items' => []
        ];

        if($request->has('client_id')) {
            $client = Client::with(['currency'])->findOrFail($request->client_id);

            array_set($form, 'client_id', $client->id);
            array_set($form, 'client', $client);
            array_set($form, 'currency_id', $client->currency->id);
            array_set($form, 'currency', $client->currency);

            // get all sent and partialy paid invoices
            $invoices = $client->invoices()->whereIn('status_id', [2, 3])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as invoice_id',
                    DB::raw('0 as amount_applied'),
                    DB::raw('0 as amount_applied_lbp'),
                    DB::raw('1 as amount_applied_lbp_rate'),
                    DB::raw('1 as amount_applied_vat_rate'),
                    DB::raw('0 as amount_applied_vat')
                ]);

            if($invoices->count()) {
                array_set($form, 'items', $invoices->toArray());
            }
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
        if ($user->is_clientpayments_create == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'payment_mode' => 'required',
            'payment_reference' => 'required_if:payment_mode,cheque',
            'document' => 'required_if:payment_mode,cheque|image|max:2048',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_received' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.invoice_id' => 'required|integer',
            'items.*.amount_applied' => ['nullable'],
            'items.*.amount_applied_lbp' => ['nullable'],
            'items.*.amount_applied_lbp_rate' => ['nullable'],
            'items.*.amount_applied_vat' => ['nullable'],
            'items.*.amount_applied_vat_rate' => ['nullable']
        ]);

        $model = new ClientPayment();
        $model->fill($request->except('items'));

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // $model->user_id = auth()->id();
        $model->status_id = ClientPayment::RECEIVED;
        $username = Auth::user()->name;
        $model ->created_by = $username;

        $model->user_id = $request->client_id;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->payment_date)->year;
        // get only applied greater than zero
        $items = collect($request->items)->map(function($item) {
            // $total_amount_applied = $item['amount_applied'] + ( $item['amount_applied_lbp'] * $item['amount_applied_lbp_rate']) + ( $item['amount_applied_vat'] * $item['amount_applied_vat_rate']);
            $total_amount_applied = (round($item['amount_applied'] + ( $item['amount_applied_lbp'] * $item['amount_applied_lbp_rate']) + ( $item['amount_applied_vat'] * $item['amount_applied_vat_rate']),2));
            
            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP + ($item['amount_applied_lbp'] + $item['amount_applied_vat']) ]);

            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                ->update(['balance' => $balanceUSD + ($item['amount_applied']) ]);

            $account_item = new AccountItems();
            $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
            $account_item->amount = $item['amount_applied_lbp'] + $item['amount_applied_vat'];
            $account_item->document = 'client_payment';
            $account_item->type = 'plus';
            $account_item->date = date('Y');
            $account_item->save();

            $account_item = new AccountItems();
            $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
            $account_item->amount = $item['amount_applied'];
            $account_item->document = 'client_payment';
            $account_item->type = 'plus';
            $account_item->date = date('Y');
            $account_item->save();
         

            if( $total_amount_applied > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        
        // throw error if amount_applied is invalid

        if($items->sum('amount_applied') != $request->amount_received ) {
            return api([
                'errors' => [
                    'amount_received' => ['Amount recived does not match amount applied'],
                ]
            ], 422);
        }

         $lbpSum = $items->sum(function($item) {
                return $item['amount_applied_lbp'] / $item['amount_applied_lbp_rate'];
            });

            $vatSum = $items->sum(function($item) {
                return $item['amount_applied_vat'] / $item['amount_applied_vat_rate'];
            });

        // throw new \Exception ($lbpSum);
        // $lbpSum = $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate');
        // $vatSum = $items->sum('amount_applied_vat') / $items->sum('amount_applied_vat_rate');
        $LbpVat = $lbpSum + $vatSum;
        if(round($LbpVat,2) != $request->amount_received_lbp ) {
            return api([
                'errors' => [
                    'amount_received_lbp' => [round($LbpVat,2) . ' '.$request->amount_received_lbp . 'Amount recived  does not match amount applied try to receive one invoice in case you have two payments in '],
                ]
            ], 422);
        }


        // $model->amount_received = $items->sum('amount_applied') + ( $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate')) + ( $items->sum('amount_applied_vat') / $items->sum('amount_applied_vat_rate'));

        // $model->amount_received_usd = $items->sum('amount_applied');
        // $model->amount_received = (round($items->sum('amount_applied') + ( $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate')) + ( $items->sum('amount_applied_vat') / $items->sum('amount_applied_vat_rate')),2));
        
        $model->amount_received = (round($items->sum('amount_applied') + $lbpSum + $vatSum,2));
        $model->amount_received_usd = (round($items->sum('amount_applied'),2));
        $model->amount_received_lbp = (round($lbpSum,2));
        $model->amount_received_lbprate = (round($vatSum,2));

        $tobepaid = DB::table('clients')
        ->where('id', $model->client_id)->value('paid');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['paid' => $tobepaid + $model->amount_received]);

        $client_balance = DB::table('clients')
        ->where('id', $model->client_id)->value('balance');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['balance' => $client_balance - $model->amount_received]);

        if($model->total == $model->amount_received){
            // DB::table('clients')
            // ->where('id', $model->client_id)
            // ->update(['last_payment_date' => date('Y-m-d')]);
        }else{
            DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['last_payment_date' => date('Y-m-d')]);
        }
        

        $payment_option_balance = PaymentOptions::where('id','=',$request->payment_option_id)->value('balance');
        DB::table('payment_options')
        ->where('id', $request->payment_option_id)
        ->update(['balance' => $payment_option_balance + $model->amount_received]);
  

        $model = DB::transaction(function() use ($model, $items) {

            $model->number = counter()->next('client_payment');

            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update invoices

            $model->items->each(function($item) {
                // $invoice = $item->invoice;
                // $amount = $invoice->amount_paid + $item->amount_applied + ($item->amount_applied_lbp / $item->amount_applied_lbp_rate) + ($item->amount_applied_vat / $item->amount_applied_vat_rate);
                $invoice = $item->invoice;
                $amount = $invoice->amount_paid + (round($item->amount_applied + ($item->amount_applied_lbp / $item->amount_applied_lbp_rate) + ($item->amount_applied_vat / $item->amount_applied_vat_rate),2));


                if($amount > $invoice->total) {
                    throw new Exception('Amount overflow');
                }

                $invoice->amount_paid = $amount;
                $invoice->status_id = Invoice::PARTIALLY_PAID;

                if($invoice->amount_paid == $invoice->total) {
                    $invoice->status_id = Invoice::PAID;
                }

                $invoice->save();
            });

            //  2. update client revenue
            $client = $model->client;
            // $client->total_revenue = $client->total_revenue + $model->amount_received + $model->amount_received_lbp ;
            // $client->save();
            $client->total_revenue = $client->total_revenue+ $model->amount_received  ;
            $client->save();

            counter()->increment('client_payment');

            return $model;
        });

        $payment_items = new PaymentOptionsItem;
        $payment_items->payment_options_id = $request->payment_option_id;
        $payment_items->payment = $model->amount_received;
        $payment_items->user_id = auth()->id();
        $payment_items->created_by = $username;
        $payment_items->date = date('Y-m-d');
        $payment_items->time = now();
        $payment_items->year_date = date('Y');
        $payment_items->document = 'client_payment';
        $payment_items->document_id = $model->id;
        $payment_items->document_number = $model->number;
        $payment_items->client_id = $model->client_id;
        $payment_items->client_name =  DB::table('clients')->where('id', $model->client_id)->value('company');
        $payment_items->save();
        
           $clien_payment_log = new ClientPaymentLog();
        $clien_payment_log->comment = "Store";
        $clien_payment_log->body = \App\ClientPayment\ClientPayment::where('id','=',$model->id)->get();
        $clien_payment_log->items = \App\ClientPayment\Item::where('client_payment_id','=',$model->id)->get();
        $clien_payment_log->save();
        

            $documentData = $model->toArray();
                $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'client_payment');
                if (!$journalVoucher) {
                    throw new \Exception ("client_payment saved but failed to create journal entries");
                }
                
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);}
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
            'data' => ClientPayment::with(['items.invoice', 'client', 'currency'])->findOrFail($id)
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
        $data = ClientPayment::with(['items.invoice', 'client', 'currency'])->findOrFail($id);
        return pdf('docs.client_payment', $data);
    }}

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_clientpayments_delete == 0){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $model = ClientPayment::findOrFail($id);
        $modelItem =Item::where('client_payment_id','=',$id)->get();
        foreach ($modelItem as $modelItemx)
        {
            
            $CPAmount = $modelItemx->amount_applied;
            $test1 = Invoice::where('id','=',$modelItemx->invoice_id)->sum('amount_paid');
            Invoice::where('id','=',$modelItemx->invoice_id)->update(['amount_paid' => $test1 - $CPAmount]);
            Invoice::where('id','=',$modelItemx->invoice_id)->update(['status_id' => '1']);
        }
        
        $modelClient =ClientPayment::where('id','=',$id)->get();
        foreach ($modelClient as $ClientItemx)
        {
            
            $CPAmount2 = $ClientItemx->amount_received;
            $test2 = Client::where('id','=',$ClientItemx->client_id)->sum('total_revenue');
            Client::where('id','=',$ClientItemx->client_id)->update(['total_revenue' => $test2 - $CPAmount2]);
        }
      
        $oldbalance =Client::where('id','=',$model->client_id)->value('balance');
        Client::where('id','=',$model->client_id)->update(['balance' => $oldbalance - $model->amount_received]);
  //Update Status to opem
  //Check ACCOUNT RECEIVABLE

        $clien_payment_log = new ClientPaymentLog();
        $clien_payment_log->comment = "Deleted";
        $clien_payment_log->body = \App\ClientPayment\ClientPayment::where('id','=',$model->id)->get();
        $clien_payment_log->items = \App\ClientPayment\Item::where('client_payment_id','=',$model->id)->get();
        $clien_payment_log->save();
        
        

        $model->items()->delete();
        $model->delete();
        // $modelItem->delete();
        // $modelItem->delete();

        return api([
            'deleted' => true
        ]);
    }}
}