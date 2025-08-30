<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorBillReport\VendorBillReport;
use App\VendorBillReport\Item as ReportItem;

use App\Bill\Bill;
use App\Bill\Item as PurchaseItem;
use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\VendorBillReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class VendorBillsReportController extends Controller
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
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
          $report = new VendorBillReport;
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


        
                $productsItem = Bill::where('vendor_id',$vendor_op,$vendor_id)
                  ->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
                  ->where('bills.date', '>=',$from_date)
                  ->where('bills.date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->bill_id = $reportX->id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_paid = $reportX->amount_paid;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->exchangerate = $reportX->exchangerate;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->bill_date = $reportX ->date;
                        $reportItem ->status_id  = Bill::where('id','=',$reportX->id)->value('status_id');
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = VendorBillReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['bills','purchase_orders','vendors','bill_items'])
            ->where('report_id','=',$RequestID->id)
            ->where('bill_date','>=',$RequestID->from_date)
            ->where('bill_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a4', 'landscape')->setWarnings(false)->loadView('docs.vendor_bills_report',compact('reports'));
           return $pdf->download(now().'vendor_bills_report.pdf');
        }
          
             // return view('docs.purchaser',compact('reports'));
        }
    }


    public function excel()
    { 
        $id = VendorBillReport::latest()->take(1)->value('id');
        return Excel::download(new VendorBillReportExcel($id), now().'vendor_bills_report.xlsx');
    }


    // public function excel()
    // {
    //     $user = auth()->user();
    //     if ($user->is_bills_view == 0 && $user->is_admin != 1){
    //             return response()->json(['error' => 'Forbidden.'], 403);
    //     }else{
    //     $ReportId1 = VendorBillReport::latest()->take(1)->get();
    //     foreach ($ReportId1 as $RequestID){
    //         $reports1 = ReportItem::join('bills','bills.id','=','bill_id')
    //         ->join('bill_items','bill_items.bill_id','=','vendor_bills_report_items.bill_id')
    //         // ->join('purchase_orders','purchase_orders.id','=','vendor_bills_report_items.purchase_order_id')
    //         ->join('products','products.id','=','bill_items.product_id')
    //         ->join('users','users.id','=','bills.user_id')
    //         ->join('vendors','vendors.id','=','bills.vendor_id')
    //         ->select(
    //         'bills.number as BL-Number','vendors.person as Vendor Name',
    //         'bill_items.qty as qty','bill_items.unit_price as unit_price',
    //          DB::raw('(bill_items.qty * bill_items.unit_price) as LineTotal'),
    //         // 'purchase_orders.number as PO Number',
    //         'products.description as Bill Items',
    //         'bills.total as Bill Total Bill Amount',
    //         'bills.amount_paid as Amount Paid USD','bills.exchangerate as ExchangeRate',
    //         'bills.date as Bill Date','bills.created_by as created_by',
           
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
    //         ->download(now().'vendor_bills_report.xlsx');
    //         }
    //     }
         
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
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
          $report = new VendorBillReport;
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
                $productsItem = Bill::where('vendor_id','=',$request->vendor_id)
                  ->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
                  ->where('bills.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('bills.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->bill_id = $reportX->id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_paid = $reportX->amount_paid;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->exchangerate = $reportX->exchangerate;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->bill_date = $reportX ->date;
                        $PR = Bill::where('id','=',$reportX->bill_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }
                        $reportItem->save();   
                    } 
            }else{
                $productsItem = Bill::join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
                ->where('bills.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('bills.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem ->report_id = $report->id;
                      $reportItem ->purchase_order_id = $reportX->purchase_order_id;
                      $reportItem ->bill_id = $reportX->id;
                      $reportItem ->vendor_id = $reportX->vendor_id;
                      $reportItem ->amount_paid = $reportX->amount_paid;
                      $reportItem ->total = $reportX->total;
                      $reportItem ->exchangerate = $reportX->exchangerate;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->bill_date = $reportX ->date;
                      $PR = Bill::where('id','=',$reportX->bill_id)->get();
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
