<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Quotation\Quotation;
use App\Rules\InvoiceBalance;
use App\Invoice\Invoice;
use App\DebitNote\DebitNote;
use Exception;
use App\Client;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\PaymentOptionsItem;
use App\PaymentOptions;
use App\Services\JournalService;

class DebitNotesController extends Controller
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
        if ($user->is_debitnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => DebitNote::with(['client', 'currency'])->orderby('created_at','desc')->search()
            ]);
        }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_debitnotes_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'client_id' => 'sometimes|required|integer|exists:clients,id',
            'quotation_id' => 'sometimes|required|integer|exists:quotations,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $form = [
            'client_id' => null,
            'client' => null,
            'number' => counter()->next('debit_notes'),
            'payment_mode' => 'none',
            'payment_reference' => 'IN-xxxxxxxx',
            'payment_date' => date('Y-m-d'),
            'amount_received' => 0,
            'amount_received_lbp'=> 0,
            'vat_received_lbp'=> 0,
            'vat_received_usd'=> 0,
            'description' => null,
            'exchangerate' =>  $exchange,
            'description' => null
        ];

        if($request->has('quotation_id')) {
            $quotation = Quotation::with(['client', 'currency'])
                ->findOrFail($request->quotation_id);

            array_set($form, 'client_id', $quotation->client->id);
            array_set($form, 'client', $quotation->client);
            array_set($form, 'currency_id', $quotation->currency->id);
            array_set($form, 'currency', $quotation->currency);
            array_set($form, 'quotation_id', $quotation->id);
            array_set($form, 'description', 'Debit Note for Quotation '.$quotation->number);

        } else if($request->has('client_id')) {
            $client = Client::with(['currency'])->findOrFail($request->client_id);

            array_set($form, 'client_id', $client->id);
            array_set($form, 'client', $client);
            array_set($form, 'currency_id', $client->currency->id);
            array_set($form, 'currency', $client->currency);

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
        if ($user->is_debitnotes_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'quotation_id' => 'sometimes|required|exists:quotations,id',
            'payment_mode' => 'required',
            'payment_reference' => 'required|min:8',
            'document' => 'required_if:payment_mode,cheque|image|max:2048',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_received' => 'nullable',
            'amount_received_lbp' => 'nullable',
            'exchangerate' => 'nullable',
            'description' => 'required|max:2000',
        ]);

        $model = new DebitNote();
        $model->fill($request->except('document'));

        $model->applied_amount = round($model->amount_received ,3);
        $username = Auth::user()->name;
        $model ->created_by = $username;


        $model->total = round($model->amount_received + ($model->amount_received_lbp / $model->exchangerate)
         + $model->vat_received_usd + ($model->vat_received_lbp / $model->exchangerate) ,3);

                       $model->balance = round($model->amount_received + ($model->amount_received_lbp / $model->exchangerate)
         + $model->vat_received_usd + ($model->vat_received_lbp / $model->exchangerate) ,3);

        // $model->user_id = auth()->id();
        $model->status_id = DebitNote::RECEIVED;

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        $model->quotation_id = $request->get('quotation_id', null);

        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('debit_notes');
            $model->save();

            // update client unused credit amount
            $client = $model->client;
            $client->unused_credit = $client->unused_credit - $model->amount_received;
            
             $client->total_revenue = $client->total_revenue - $model->amount_received  ;
             
            $client->save();

            counter()->increment('debit_notes');

            return $model;
        });

 $client_balance = DB::table('clients')
        ->where('id', $model->client_id)->value('balance');

        DB::table('clients')
        ->where('id', $model->client_id)
        ->update(['balance' => $client_balance + $model->amount_received]);
        

        $payment_option_balance = PaymentOptions::where('id','=',$request->payment_option_id)->value('balance');
        DB::table('payment_options')
        ->where('id', $request->payment_option_id)
        ->update(['balance' => $payment_option_balance - $model->amount_received]);
        $payment_items = new PaymentOptionsItem;
        $payment_items->payment_options_id = $request->payment_option_id;
        $payment_items->payment = $model->amount_received;
        $payment_items->user_id = auth()->id();
        $payment_items->created_by = $username;
        $payment_items->date = date('Y-m-d');
        $payment_items->time = now();
        $payment_items->year_date = date('Y');
        $payment_items->document = 'debit_note';
        $payment_items->document_id = $model->id;
        $payment_items->document_number = $model->number;
        $payment_items->client_id = $model->client_id;
        $payment_items->client_name =  DB::table('clients')->where('id', $model->client_id)->value('company');
        $payment_items->save();

           $documentData = $model->toArray();
                $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'debit_note');
                if (!$journalVoucher) {
                    throw new \Exception ("debit_note saved but failed to create journal entries");
                }

        return api([
            'saved' => true,
            'id' => $model->id
        ]);}
        
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_debitnotes_view== 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = DebitNote::with(['client', 'currency', 'quotation', 'items.invoice'])
            ->findOrFail($id);
        return api([
            'data' => $data
        ]);
    }
    }

    public function showInvoices($id)
    {
        $data = DebitNote::with(['client', 'currency'])
            // ->whereStatusId(DebitNote::RECEIVED)
            ->whereIn('status_id',['1','5'])
            ->findOrFail($id);
        $data->items = $data->client->invoices()->whereIn('status_id', [2, 3])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as invoice_id',
                    DB::raw('0 as amount_applied')
                ]);
        return api([
            'data' => $data
        ]);
    }

    public function applyInvoices($id, Request $request)
    {
        $model = DebitNote::whereIn('status_id',['1','5'])
        // whereStatusId(DebitNote::RECEIVED)
            ->findOrFail($id);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.invoice_id' => 'required|integer',
            'items.*.amount_applied' => ['required', 'numeric', 'min:0',  'invoice_balance:items.*.invoice_id']
        ]);

        $items = collect($request->items)->map(function($item) {
            if($item['amount_applied'] > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        // throw error if amount_applied is invalid or less
        if($items->sum('amount_applied') > $model->balance) {
            return api([
                'errors' => [
                    'applied_amount' => ['Amount recived does not match amount applied']
                ]
            ], 422);
        }

        $getAmountReceived = DB::table('debit_notes')->where('id','=',$id)->value('amount_received');
        $getAmountReceovedLBP =  DB::table('debit_notes')->where('id','=',$id)->value('amount_received_lbp');

        $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
        $updateAccountLBP = DB::table('accounts')
                            ->where('currency_id','=','2')
                            ->update(['balance' => $balanceLBP - $getAmountReceovedLBP ]);

        $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
        $updateAccountUSD = DB::table('accounts')
                            ->where('currency_id','=','1')
                            ->update(['balance' => $balanceUSD - $getAmountReceived  ]);

        $model->applied_amount = $items->sum('amount_applied');
        $model->applied_date = date('Y-m-d');

        $username = Auth::user()->name;
        $model ->created_by = $username;
        
        $model = DB::transaction(function() use ($model, $items) {
                 //  throw new \Exception($items->sum('amount_applied'));
            if($items->sum('amount_applied') < $model->balance){
                 $model->status_id = DebitNote::PARTIALLYDRAWN;
                    $model->balance = $model->balance - $items->sum('amount_applied');
            }else{
                 $model->status_id = DebitNote::DRAWN;
            }
            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update invoices

            $model->items->each(function($item) {
                $invoice = $item->invoice;
                $amount = $invoice->debit_amount + $item->amount_applied;

                     if (bccomp((string) $amount, (string) $invoice->total, 2) === 1) {
    throw new \Exception("Amount overflow: {$amount} > {$invoice->total} on Invoice {$invoice->number}");
}
                $invoice_debit_amount = $invoice->debit_amount;
                // $invoice->amount_paid = $invoice->amount_paid - $amount;
                $invoice->amount_paid = $invoice->amount_paid - $item->amount_applied;
                $invoice->debit_amount = $invoice_debit_amount + $item->amount_applied;
                $invoice->status_id = Invoice::PARTIALLY_PAID;

                if($invoice->debit_amount == $invoice->total) {
                    $invoice->status_id = Invoice::PAID;
                }

                $invoice->save();
            });

            //  2. update client revenue and also reduce unused credit
            $client = $model->client;
            $total = $model->applied_amount;
            $client->total_revenue = $client->total_revenue - $total;
            $client->unused_credit = $client->unused_credit + $total;
            $client->save();

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_debitnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = DebitNote::with(['client', 'currency', 'quotation'])
            ->findOrFail($id);
        return pdf('docs.debit_notes', $data);}
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_debitnotes_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = DebitNote::findOrFail($id);

        // check whether this particular advance payment belongs to
        // deposit, invoice, etc.
        // if yes provide warning

        if(true) {
            return api([
                'warning' => 'Delete all the advance payment relations first'
            ], 422);
        }

        $model->delete();

        return api([
            'deleted' => true
        ]);
    }}
}
