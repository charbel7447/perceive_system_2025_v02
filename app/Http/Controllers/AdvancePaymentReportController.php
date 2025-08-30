<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdvancePaymentReport\AdvancePaymentReport;
use App\AdvancePaymentReport\Item as ReportItem;

use App\AdvancePayment\AdvancePayment;
use App\AdvancePayment\Item as PurchaseItem;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;


use App\Excel\AdvancePaymentReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class AdvancePaymentReportController extends Controller
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
      if ($user->is_advancepayments_create == 0 && $user->is_admin != 1){
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
      $user = auth()->user();
      if ($user->is_advancepayments_create == 0 && $user->is_admin != 1){
              return response()->json(['error' => 'Forbidden.'], 403);
      }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'client_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new AdvancePaymentReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->client_id = $request->input('client_id');
          
          $from_date= 0;
          $to_date = 0;
          if($request->input('from_date')){
            $report ->from_date = $request->input('from_date');
            $from_date = $request->input('from_date');
          }else{
            $report ->from_date = '1990-01-01';
            $from_date = '1990-01-01';
          }
          if($request->input('to_date')){
            $report ->to_date = $request->input('to_date');
            $to_date = $request->input('to_date');
          }else{
            $report ->to_date = '2030-01-01';
            $to_date = '2030-01-01';
          }
          
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save();  
          
          
          $client_id = $request->client_id;
          if($client_id > 0){
            $client_id = $client_id;
            $client_op = "=";
          }else{
            $client_id = null;
            $client_op = "!=";
          } 

          $productsItem = PurchaseItem::where('client_id',$client_op,$client_id)
                  ->join('advance_payments', 'advance_payments.id', '=', 'advance_payment_items.advance_payment_id')
                  ->where('advance_payments.payment_date', '>=',$from_date)
                  ->where('advance_payments.payment_date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> advance_payment_id = $reportX->advance_payment_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->advance_payment_date = $reportX ->created_at;
                        $PR = AdvancePayment::where('id','=',$reportX->advance_payment_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 

          return api([
            'saved' => true,
            'id' => $report->id
        ]);
      }
    }


    public function pdf()
    {
      $user = auth()->user();
      if ($user->is_advancepayments_create == 0 && $user->is_admin != 1){
              return response()->json(['error' => 'Forbidden.'], 403);
      }else{
        $ReportId1 = AdvancePaymentReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['advance_payments','uom','product','products','clients','invoices'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>',1)
            ->where('advance_payment_date','>=',$RequestID->from_date)
            ->where('advance_payment_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.advance_payments_report',compact('reports'));
           return $pdf->download(now().'advance_payments.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
      }
    }


    public function excel()
    { 
        $id = AdvancePaymentReport::latest()->take(1)->value('id');
        return Excel::download(new AdvancePaymentReportExcel($id), now().'advances.xlsx');
    }
    
    // public function excel()
    // {
    //   $user = auth()->user();
    //   if ($user->is_advancepayments_create == 0 && $user->is_admin != 1){
    //           return response()->json(['error' => 'Forbidden.'], 403);
    //   }else{
    //     $ReportId1 = AdvancePaymentReport::latest()->take(1)->get();
    //     foreach ($ReportId1 as $RequestID){
    //         $reports1 = ReportItem::join('advance_payments','advance_payments.id','=','advance_payment_id')
   
    //         ->join('users','users.id','=','advance_payments.user_id')
    //         ->join('invoices','invoices.id','=','invoice_id')
    //         ->join('clients','clients.id','=','advance_payments.client_id')
    //         ->select(
    //         'advance_payments.number as AD-Number','clients.person as Client Name',
    //         'invoices.number as Applied Invoice','advance_payments.amount_received',
    //         'advance_payments.payment_date as Payment Date','advance_payments.created_by as created_by',
           
    //         )
    //         ->where('report_id','=',$RequestID->id)
    //         ->where('advance_payments.status_id','>',2)
    //         ->get();
           
    //         $header_style = (new StyleBuilder())->setCellAlignment('center')->setFontBold()->setBackgroundColor("EDEDED")->build();

    //         $rows_style = (new StyleBuilder())
    //             ->setFontSize(15)
    //             ->setShouldWrapText()
    //             ->setBackgroundColor("EDEDED")
    //             ->build();

    //         // (new FastExcel($reports1))->download(now().'purchase_requests.xlsx');
    //         return (new FastExcel($reports1))
    //         ->headerStyle($header_style)
    //         // ->rowsStyle($rows_style)
    //         ->download(now().'advance_payments.xlsx');
         
    //     }
    //   }
    //          // return view('docs.purchaser',compact('reports'));
    // }

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
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'client_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new AdvancePaymentReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->client_id = $request->input('client_id');
          if($request->input('from_date')){
            $report ->from_date = $request->input('from_date').' 01:00:00';
          }
          if($request->input('to_date')){
            $report ->to_date = $request->input('to_date').' 00:00:00';
          }
          
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save();  
          
         

        if ($request->client_id > 0){
                $productsItem = PurchaseItem::where('client_id','=',$request->client_id)
                  ->join('advance_payments', 'advance_payments.id', '=', 'advance_payment_items.advance_payment_id')
                  ->where('advance_payments.payment_date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('advance_payments.payment_date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> advance_payment_id = $reportX->advance_payment_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->advance_payment_date = $reportX ->date;
                        $PR = AdvancePayment::where('id','=',$reportX->advance_payment_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = PurchaseItem::join('advance_payments', 'advance_payments.id', '=', 'advance_payment_items.advance_payment_id')
                ->where('advance_payments.payment_date', '>=',$request->input('from_date').' 01:00:00')
                ->where('advance_payments.payment_date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                        $reportItem -> advance_payment_id = $reportX->advance_payment_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->advance_payment_date = $reportX ->date;
                      $PR = AdvancePayment::where('id','=',$reportX->advance_payment_id)->get();
                      foreach ($PR as $PRStatus){
                          $reportItem ->status_id = $PRStatus ->status_id;
                      }

                      $reportItem->save();   
                  } 
            }



          return api([
            'saved' => true,
            'id' => $report->id
        ]);
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
