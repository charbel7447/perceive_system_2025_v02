<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientBalanceReport\ClientBalanceReport;
use App\ClientBalanceReport\Item as ClientBalanceReportItem;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\ClientBalance as ClientBalance;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ClientsBalanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $user = auth()->user();
        if ($user->is_clientpayments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
          $form = array_merge([
              'user_id' => '',
              'product_id' => '',
              'client_id' => '',
              'created_by' => '',
              'from_date' => '',
              'to_date' => ''
          
          ]);

          return api([
              'form' => $form
          ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $username = Auth::user()->id;
        // $inputs = $request->all();
        ClientBalanceReportItem::truncate();
        ClientBalanceReport::truncate();
        $report = new ClientBalanceReport;
        $report ->user_id = $username;
        $report ->client_id = $request->input('client_id');
        $username = Auth::user()->name;
        $report ->created_by = $username;
        // $report = Statement::Create($inputs); ->format('d/m/Y') 
        $report->save();  
        $client_id = $request->client_id;

        // DB::table('test6')
        // ->where('id','=','1')
        // ->update(['body' => date('Y-m-d', strtotime("-30 days"))]);

        // DB::table('test6')
        // ->where('id','=','2')
        // ->update(['body' => date('Y-m-d', strtotime("-60 days"))]);

        // DB::table('test6')
        // ->where('id','=','3')
        // ->update(['body' => date('Y-m-d', strtotime("-90 days"))]);

        // DB::table('test6')
        // ->where('id','=','4')
        // ->update(['body' => date('Y-m-d')]);

        if($client_id > 0){
          $client_id = $client_id;
        }else{
          $client_id = null;
        }
       
        if($client_id > 0){
            $Balance = 0;   $IN30 = 0;   $IN60 = 0;   $IN90 = 0;   $Paid30 = 0;   $Paid60 = 0;  $Paid90 = 0;  
            $Balance30 = 0; $INPaid30 = 0; $DNPaid30 = 0; $CPPaid30 = 0; $ADPaid30 = 0; $CNPaid30 = 0;
            $Balance60 = 0; $INPaid60 = 0;   $DNPaid60 = 0; $CPPaid60 = 0;   $ADPaid60 = 0; $CNPaid60 = 0;
            $Balance90 = 0; $INPaid90 = 0;   $DNPaid90 = 0; $CPPaid90 = 0;   $ADPaid90 = 0; $CNPaid90 = 0;


            $IN30 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid30 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->sum('amount_paid');

            $Credits30 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits30 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance30 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            // $Balance30 = $IN30 + $Debits30 - $Paid30 - $Credits30 - $Advance30;         
            $Balance30 = $IN30 - $Paid30;                
            

            $IN60 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid60 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->sum('amount_paid');

            $Credits60 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits60 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance60 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            //$Balance60 = $IN60 + $Debits60 - $Paid60 - $Credits60 - $Advance60;
            $Balance60 = $IN60 - $Paid60;    

            $IN90 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid90 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->sum('amount_paid');

            $Credits90 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits90 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance90 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

           // $Balance90 = $IN90 + $Debits90 - $Paid90 - $Credits90 - $Advance90;
            $Balance90 = $IN90 - $Paid90;    


            $IN = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->sum('amount_paid');

            $Credits = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Balance = $IN + $Debits - $Paid - $Credits - $Advance;


            $item = new ClientBalanceReportItem;
            $item->client_balance_report_id = $report->id;
            $item->client_id = $client_id;
            $item->name = \App\Client::where('id','=',$client_id)->value('name');
            $item->reference = \App\Client::where('id','=',$client_id)->value('ref_number');
            $item->balance = $Balance;
            $item->balance_30 = $Balance30;
            $item->balance_60 = $Balance60;
            $item->balance_90 = $Balance90;
            $item->save();

        }else{
            $clients = \App\Client::get();
            foreach($clients as $client){
                $Balance = 0;   $INPaid = 0;   $DNPaid = 0;   $CPPaid = 0;   $ADPaid = 0;   $CNPaid = 0;   
                $Balance30 = 0; $INPaid30 = 0; $DNPaid30 = 0; $CPPaid30 = 0; $ADPaid30 = 0; $CNPaid30 = 0;
                $Balance60 = 0; $INPaid60 = 0;   $DNPaid60 = 0; $CPPaid60 = 0;   $ADPaid60 = 0; $CNPaid60 = 0;
                $Balance90 = 0; $INPaid90 = 0;   $DNPaid90 = 0; $CPPaid90 = 0;   $ADPaid90 = 0; $CNPaid90 = 0;
                $pending = 0;

                $INPaid = \App\Invoice\Invoice::where('client_id','=',$client->id)
                ->sum('total');
                $invoicesItemPaid = \App\Invoice\Invoice::where('client_id','=',$client->id)
                        ->sum('amount_paid');
                $pending = $INPaid - $invoicesItemPaid;
                if($pending > 0){
                    $client_id = $client->id;

                    $IN30 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid30 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->sum('amount_paid');

            $Credits30 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits30 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance30 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-30 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            // $Balance30 = $IN30 + $Debits30 - $Paid30 - $Credits30 - $Advance30;         
            $Balance30 = $IN30 - $Paid30;                
            

            $IN60 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid60 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->sum('amount_paid');

            $Credits60 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits60 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance60 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<', date('Y-m-d', strtotime("-30 days")))
                            ->where('payment_date', '>=', date('Y-m-d', strtotime("-60 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            //$Balance60 = $IN60 + $Debits60 - $Paid60 - $Credits60 - $Advance60;
            $Balance60 = $IN60 - $Paid60;    

            $IN90 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid90 = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->sum('amount_paid');

            $Credits90 = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits90 = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance90 = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<',  date('Y-m-d', strtotime("-60 days")))
                            // ->where('payment_date', '>=', date('Y-m-d', strtotime("-90 days")))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

           // $Balance90 = $IN90 + $Debits90 - $Paid90 - $Credits90 - $Advance90;
            $Balance90 = $IN90 - $Paid90;    


            $IN = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->where('status_id','>',1)
                            ->sum('total');

            $Paid = \App\Invoice\Invoice::where('client_id','=',$client_id)
                            ->where('date', '<=', date('Y-m-d'))
                            ->sum('amount_paid');

            $Credits = \App\CreditNote\CreditNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Debits = \App\DebitNote\DebitNote::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');       
                            
            $Advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client_id)
                            ->where('payment_date', '<=', date('Y-m-d'))
                            ->where('status_id','=',1)
                            ->sum('amount_received');

            $Balance = $IN + $Debits - $Paid - $Credits - $Advance;

                  
                    $Balance00 = $Balance30 + $Balance90 + $Balance60;

                    if($Balance00 > 0 || $Balance > 0){
                        $item = new ClientBalanceReportItem;
                        $item->client_balance_report_id = $report->id;
                        $item->client_id = $client->id;
                        $seller_id = \App\Client::where('id','=',$client->id)->value('seller_id');
                        $item->seller_id = \App\Client::where('id','=',$client->id)->value('seller_id');
                        $item->seller_name = \App\Sellers\Sellers::where('id','=',$seller_id)->value('name');
                        $item->name = \App\Client::where('id','=',$client->id)->value('name');
                        $item->company = \App\Client::where('id','=',$client->id)->value('company');
                        $item->reference = \App\Client::where('id','=',$client->id)->value('ref_number');
                        $item->balance = $Balance;
                        $item->balance_30 = $Balance30;
                        $item->balance_60 = $Balance60;
                        $item->balance_90 = $Balance90;
                        $item->balance_00 = $Balance00;
                        $item->save();
                    }
                }
            }
        }
        return api([
            'saved' => true,
            'id' => $report->id
        ]);
               
    }


    public function pdf()
    {
        $user = auth()->user();
        if ($user->is_clientpayments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $report_id = \App\ClientBalanceReport\ClientBalanceReport::latest()->take(1)->value('id');
            $reports = \App\ClientBalanceReport\Item::where('client_balance_report_id','=',$report_id)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.client_balance_report',compact('reports'));
           return $pdf->download(now().'client_balance_report.pdf');
            // return view('docs.client_balance_report',compact('reports'));
        }
    }

    public function excel()
    { 
        $id = \App\ClientBalanceReport\ClientBalanceReport::latest()->take(1)->value('id');
        return Excel::download(new ClientBalance($id), now().'clients_balance_report.xlsx');
    }

   


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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
        //
    }
}
