<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Invoice\Invoice;
// use App\Invoice\Item;
use DB;
use PDF;
use App\ClientPayment\ClientPayment;
use App\Rules\InvoiceBalance;
use App\AdvancePayment\AdvancePayment;
use App\CreditNote\CreditNote;
use App\DebitNote\DebitNote;
use App\Product\Product;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;
use App\Statement\Statement;
use App\Statement\Item;
use Charts;
use Auth;
use App\User;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;


class KabbouchiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_clientsoa_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Client::search()
            ]);
        }
    }


        public function search()
    {
        $user = auth()->user();
        if ($user->is_soa == 0 && $user->is_admin != 1){
        $results = Client::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                $query->where('company', 'like', '%'.request('q').'%')
                    ->orWhere('person', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'person', 'company', 'currency_id'])
            ->when(request('with') == 'invoices', function($clients) {
                return $clients->map(function($client) {
                    $client->invoices = $client->invoices()->whereIn('status_id', [2, 3])
                        ->get([
                            'amount_paid', 'total', 'date', 'status_id', 'due_date',
                            'number', 'id as invoice_id',
                            DB::raw('0 as amount_applied')
                        ]);
                    return $client;
                });
            });

        return api([
            'results' => $results
        ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        if ($user->is_clientsoa_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $client = Client::with(['currency'])->findOrFail($id);
        $stats = [
            'total_revenue' => $client->total_revenue,
            'account_receivable' => $client->invoices()->whereIn('status_id', [2, 3])->sum(DB::raw('total - amount_paid')),
            'unused_credit' => $client->unused_credit,
            'advance_payments' => $client->advancePayments()->whereIn('status_id', [1, 2])->count(),
            'open_sales_orders' => $client->salesOrders()->whereIn('status_id', [3])->count(),
            'unpaid_invoices' => $client->invoices()->whereIn('status_id', [2, 3])->count()
        ];
        return api([
            'data' => $client,
            'stats' => $stats
        ]);}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_clientsoa_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
         return api([
            'form' => Client::with(['currency'])->findOrFail($id)
        ]);
    }
    }

    public function report($id)
    {
         return ('statement.report');
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
        $user = auth()->user();
        if ($user->is_clientsoa_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'person' => 'nullable|max:255',
            'company' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d'
        ]);

        Statement::truncate();
        Item::truncate();
        // $inputs = $request->all();
        $statement = new Statement;
        $statement ->client_id = $id;
        $statement ->person = $request->input('person');
        $statement ->company = $request->input('company');
        $statement ->date = $request->input('date');
        $statement ->due_date = $request->input('due_date');
        $username = Auth::user()->name;
        $statement ->created_by = $username;
        // $statement = Statement::Create($inputs);
        $statement->save();   

        //Invoices
        $invoices1 = Invoice::where('date','>=',$request->input('date'))
        ->where('date','<=',$request->input('due_date'))->where('client_id','=',$id)->where('status_id','!=','1')->get();
    
        foreach ($invoices1 as $Inv1) 
        {
            $statementIN = new Item;
            $statementIN ->statement_id = $statement->id;
            $statementIN ->client_id = $id;
            $statementIN ->reference_id = $Inv1->id;
            $statementIN ->amount_applied = $Inv1->total;
            $statementIN ->reference_date = $Inv1->date;
            $statementIN ->type = 'invoice';
            $statementIN ->reference_number = $Inv1->number;
            $statementIN->save(); 
        }


        //client Payments
        $payments1 = ClientPayment::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('client_id','=',$id)->get();
    
        foreach ($payments1 as $Pay1)
        {
            $statementPay = new Item;
            $statementPay ->statement_id = $statement->id;
            $statementPay ->client_id = $id;
            $statementPay ->reference_id = $Pay1->id;
            $statementPay ->amount_applied = $Pay1->amount_received;
            $statementPay ->reference_date = $Pay1->payment_date;
            $statementPay ->type = 'clientpayment';
            $statementPay ->reference_number = $Pay1->number;
            $statementPay->save(); 
        }

         //ReceiptVoucher
        $payments1 = \App\ReceiptVoucher\ReceiptVoucher::where('date','>=',$request->input('date'))
        ->where('date','<=',$request->input('due_date'))->where('client_id','=',$id)->get();
    
        foreach ($payments1 as $Pay1)
        {
            $statementPay = new Item;
            $statementPay ->statement_id = $statement->id;
            $statementPay ->client_id = $id;
            $statementPay ->reference_id = $Pay1->id;
            $statementPay ->amount_applied = $Pay1->total_debit_usd;
            $statementPay ->amount_applied_vat = $Pay1->total_debit_vat;
            $statementPay ->reference_date = $Pay1->date;
            $statementPay ->type = 'receiptvoucher';
            $statementPay ->reference_number = $Pay1->number;
            $statementPay->save(); 
        }

        //ad Payments
        $adpayments1 = AdvancePayment::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('client_id','=',$id)->get();
    
        foreach ($adpayments1 as $AdPay1)
        {
            $statementAd = new Item;
            $statementAd ->statement_id = $statement->id;
            $statementAd ->client_id = $id;
            $statementAd ->reference_id = $AdPay1->id;
            $statementAd ->amount_applied = $AdPay1->amount_received;
            $statementAd ->reference_date = $AdPay1->payment_date;
            $statementAd ->type = 'advancepayment';
            $statementAd ->reference_number = $AdPay1->number;
            $statementAd->save(); 
        }

        //credit note
        $cdpayments1 = CreditNote::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('client_id','=',$id)->get();
    
        foreach ($cdpayments1 as $cdPay1)
        {
            $statementCd = new Item;
            $statementCd ->statement_id = $statement->id;
            $statementCd ->client_id = $id;
            $statementCd ->reference_id = $cdPay1->id;
            $statementCd ->amount_applied = $cdPay1->amount_received;
            $statementCd ->reference_date = $cdPay1->payment_date;
            $statementCd ->type = 'creditnote';
            $statementCd ->reference_number = $cdPay1->number;
            $statementCd->save(); 
        }

        //debit note
        $dnpayments1 = DebitNote::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('client_id','=',$id)->get();
    
        foreach ($dnpayments1 as $dnPay1)
        {
            $statementDn = new Item;
            $statementDn ->statement_id = $statement->id;
            $statementDn ->client_id = $id;
            $statementDn ->reference_id = $dnPay1->id;
            $statementDn ->amount_applied = $dnPay1->amount_received;
            $statementDn ->reference_date = $dnPay1->payment_date;
            $statementDn ->type = 'debitnote';
            $statementDn ->reference_number = $dnPay1->number;
            $statementDn->save(); 
        }
        

        return api(['saved' => true]);
    }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_clientsoa_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
         $statement = Statement::latest()->latest()->take(1)->get();
         $dateS = Statement::select('date')->latest()->take(1)->get();
         $dateE = Statement::select('due_date')->latest()->take(1)->get();
        
         $client = Client::findOrFail($id);

         foreach ($dateS as $startdate) {
                
                $StartDates = $startdate->date;

            foreach ($dateE as $enddate) {

                $EndDates = $enddate->due_date;

         $invoices = Invoice::where('date','>=',$StartDates)
        ->where('date','<=',$EndDates)->where('client_id','=',$id)->where('status_id','!=','1')->get();

        //  $invoicesItems = Item::where('created_at','>=',$StartDates)
        // ->where('created_at','<=',$EndDates)->get();

         $payment = ClientPayment::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('client_id','=',$id)->get();

         $advancepayment = AdvancePayment::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('client_id','=',$id)->get();

        $creditnotes = CreditNote::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('client_id','=',$id)->get();

        $debitnotes = DebitNote::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('client_id','=',$id)->get();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.statement', compact('client','invoices','payment','advancepayment','statement','dateS','dateE','EndDates','StartDates','creditnotes','debitnotes'));

    //return view('docs.statement', compact('client','invoices','payment','advancepayment','statement','dateS','dateE','EndDates','StartDates'));
        //  return $pdf->download('statement.pdf');
        $name = Statement::where('client_id','=',$id)->select('company')->latest()->take(1)->get();
        foreach($name as $person){
            return $pdf->download(now().'--'.$person->company.'--Statement.pdf');
        }
        // return pdf('docs.statement', $data);
      //'id', '=', $request->get('product')
}
         }

    }}

}
