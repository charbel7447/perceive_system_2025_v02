<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExpensesReport\ExpensesReport;
use App\ExpensesReport\Item as ReportItem;

use App\Expense;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\ExpensesReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
class ExpensesReportController extends Controller
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
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'user_id' => '',
            'product_id' => '',
            'vendor_id' => '',
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
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'vendor_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new ExpensesReport;
          $report ->user_id = $username;
          $report ->vendor_id = $request->input('vendor_id');
          
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
          
          $vendor_id = $request->vendor_id;
          if($vendor_id > 0){
            $vendor_id = $vendor_id;
            $vendor_op = "=";
          }else{
            $vendor_id = null;
            $vendor_op = "!=";
          }

         
          $productsItem = Expense::where('vendor_id',$vendor_op,$vendor_id)
                  ->where('expenses.payment_date', '>=',$from_date)
                  ->where('expenses.payment_date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> expenses_id = $reportX->id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_paid = $reportX->amount_paid;
                        $reportItem ->amount_paid_lbp = $reportX->amount_paid_lbp;
                        $reportItem ->exchangerate = $reportX->exchangrate;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->payment_date = $reportX ->payment_date;
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
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = ExpensesReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['expenses','vendors'])
            ->where('report_id','=',$RequestID->id)
            ->where('payment_date','>=',$RequestID->from_date)
            ->where('payment_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.expenses_report',compact('reports'));
           return $pdf->download(now().'expenses_payments.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
      }
    }

    public function excel()
    { 
        $id = ExpensesReport::latest()->take(1)->value('id');
        return Excel::download(new ExpensesReportExcel($id), now().'expenses_payments.xlsx');
    }

    // public function excel()
    // {
    //   $user = auth()->user();
    //     if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
    //             return response()->json(['error' => 'Forbidden.'], 403);
    //     }else{
    //     $ReportId1 = ExpensesReport::latest()->take(1)->get();
    //     foreach ($ReportId1 as $RequestID){
    //         $reports1 = ReportItem::join('expenses','expenses.id','=','expenses_id')
   
    //         // ->join('users','users.id','=','expenses.user_id')
    //         ->join('vendors','vendors.id','=','expenses.vendor_id')
    //         ->select(
    //         'expenses.number as EX-Number','vendors.person as Client Name',
    //         'expenses.amount_paid as Amount Paid USD','expenses.amount_paid_lbp as Amount Paid LBP',
    //         'expenses.exchangerate as ExchangeRate',
    //         'expenses.payment_date as Payment Date','expenses.created_by as created_by',
           
    //         )
    //         ->where('report_id','=',$RequestID->id)
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
    //         ->download(now().'expenses_payments.xlsx');
         
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
      $user = auth()->user();
        if ($user->is_vendorexpenses_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'vendor_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new ExpensesReport;
          $report ->user_id = $username;
          $report ->vendor_id = $request->input('vendor_id');
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
          
         

        if ($request->vendor_id > 0){
                $productsItem = Expense::where('vendor_id','=',$request->vendor_id)
                  ->where('expenses.payment_date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('expenses.payment_date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> expenses_id = $reportX->id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_paid = $reportX->amount_paid;
                        $reportItem ->amount_paid_lbp = $reportX->amount_paid_lbp;
                        $reportItem ->exchangerate = $reportX->exchangrate;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->payment_date = $reportX ->payment_date;
                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = Expense::where('expenses.payment_date', '>=',$request->input('from_date').' 01:00:00')
                ->where('expenses.payment_date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                    $reportItem = new ReportItem;
                    $reportItem -> report_id = $report->id;
                    $reportItem -> expenses_id = $reportX->id;
                    $reportItem ->vendor_id = $reportX->vendor_id;
                    $reportItem ->amount_paid = $reportX->amount_paid;
                    $reportItem ->amount_paid_lbp = $reportX->amount_paid_lbp;
                    $reportItem ->exchangerate = $reportX->exchangrate;
                    $reportItem ->from_date = $report ->from_date;
                    $reportItem ->to_date = $report ->to_date;
                    $reportItem ->payment_date = $reportX ->payment_date;
                    $reportItem->save();    
                  } 
            }



          return api([
            'saved' => true,
            'id' => $report->id
        ]);
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
}
