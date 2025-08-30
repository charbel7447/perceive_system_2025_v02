<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorPaymentReport\VendorPaymentReport;
use App\VendorPaymentReport\Item as ReportItem;

use App\VendorPayment\VendorPayment;
use App\VendorPayment\Item as PurchaseItem;
use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;


use App\Excel\VendorPaymentsReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
class VendorPaymentsReportController extends Controller
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
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
          $report = new VendorPaymentReport;
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

          $productsItem = PurchaseItem::where('vendor_id',$vendor_op,$vendor_id)
                  ->join('vendor_payments', 'vendor_payments.id', '=', 'vendor_payment_items.vendor_payment_id')
                  ->where('vendor_payments.payment_date', '>=',$from_date)
                  ->where('vendor_payments.payment_date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->vendor_payment_id = $reportX->vendor_payment_id;
                        $reportItem ->bill_id = $reportX->bill_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_applied_lbp = $reportX->amount_applied_lbp;
                        $reportItem ->amount_applied_lbp_rate = $reportX->amount_applied_lbp_rate;
                        $reportItem ->amount_applied_vat = $reportX->amount_applied_vat;
                        $reportItem ->amount_applied_vat_rate = $reportX->amount_applied_vat_rate;
                        $reportItem ->payment_mode = $reportX->payment_mode;
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = VendorPaymentReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['vendor_bills','vendor_payments','vendors'])
            ->where('report_id','=',$RequestID->id)
            ->where('payment_date','>=',$RequestID->from_date)
            ->where('payment_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.vendor_payments_report',compact('reports'));
           return $pdf->download(now().'vendor_payments_report.pdf');

        }
             // return view('docs.purchaser',compact('reports'));
        }
    }

    public function excel()
    { 
        $id = VendorPaymentReport::latest()->take(1)->value('id');
        return Excel::download(new VendorPaymentsReportExcel($id), now().'vendor_payments_report.xlsx');
    }


    // public function excel()
    // {
    //   $user = auth()->user();
    //     if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
    //             return response()->json(['error' => 'Forbidden.'], 403);
    //     }else{
    //     $ReportId1 = VendorPaymentReport::latest()->take(1)->get();
    //     foreach ($ReportId1 as $RequestID){
    //         $reports1 = ReportItem::join('vendor_payments','vendor_payments.id','=','vendor_payment_id')
    //         ->join('vendor_payment_items','vendor_payment_items.vendor_payment_id','=','vendor_payments_report_items.vendor_payment_id')
    //         ->join('bills','bills.id','=','vendor_payments_report_items.bill_id')
    //         // ->join('users','users.id','=','vendor_payments.user_id')
    //         ->join('vendors','vendors.id','=','vendor_payments.vendor_id')
    //         ->select(
    //         'vendor_payments.number as EX-Number','vendors.person as Vendor Name',
    //         'bills.number as Bill Number','bills.total as Bill Total Amount',
    //         'vendor_payment_items.amount_applied as Amount Paid USD',
    //         'vendor_payment_items.amount_applied_lbp as Amount Paid LBP','vendor_payment_items.amount_applied_lbp_rate as Amount Paid LBP Rate',
    //         'vendor_payment_items.amount_applied_vat as Amount Paid Vat','vendor_payment_items.amount_applied_vat_rate as Amount Paid Vat Rate',
    //         'vendor_payments.payment_date as Payment Date','vendor_payments.created_by as created_by',
           
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
    //         ->download(now().'vendor_payments_report.xlsx');
         
    //     }
    //   }
         
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
        if ($user->is_vendorpayments_view == 0 && $user->is_admin != 1){
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
          $report = new VendorPaymentReport;
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
                $productsItem = PurchaseItem::where('vendor_id','=',$request->vendor_id)
                  ->join('vendor_payments', 'vendor_payments.id', '=', 'vendor_payment_items.vendor_payment_id')
                  ->where('vendor_payments.payment_date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('vendor_payments.payment_date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->vendor_payment_id = $reportX->vendor_payment_id;
                        $reportItem ->bill_id = $reportX->bill_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_applied_lbp = $reportX->amount_applied_lbp;
                        $reportItem ->amount_applied_lbp_rate = $reportX->amount_applied_lbp_rate;
                        $reportItem ->amount_applied_vat = $reportX->amount_applied_vat;
                        $reportItem ->amount_applied_vat_rate = $reportX->amount_applied_vat_rate;
                        $reportItem ->payment_mode = $reportX->payment_mode;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->payment_date = $reportX ->payment_date;
                        $reportItem->save();   
                    } 
            }else{
                $productsItem = PurchaseItem::join('vendor_payments', 'vendor_payments.id', '=', 'vendor_payment_items.vendor_payment_id')
                  ->where('vendor_payments.payment_date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('vendor_payments.payment_date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                  foreach ($productsItem as $reportX)
                  {
                    $reportItem = new ReportItem;
                    $reportItem ->report_id = $report->id;
                    $reportItem ->vendor_payment_id = $reportX->vendor_payment_id;
                    $reportItem ->bill_id = $reportX->bill_id;
                    $reportItem ->vendor_id = $reportX->vendor_id;
                    $reportItem ->amount_applied = $reportX->amount_applied;
                    $reportItem ->amount_applied_lbp = $reportX->amount_applied_lbp;
                    $reportItem ->amount_applied_lbp_rate = $reportX->amount_applied_lbp_rate;
                    $reportItem ->amount_applied_vat = $reportX->amount_applied_vat;
                    $reportItem ->amount_applied_vat_rate = $reportX->amount_applied_vat_rate;
                    $reportItem ->payment_mode = $reportX->payment_mode;
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
