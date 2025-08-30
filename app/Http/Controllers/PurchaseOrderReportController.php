<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Category;
use App\Vendor;
use DB;
use Auth;
use PDF;
use App\Product\Product as ProductItem;
use App\Product\Items;
use App\Uom;
use App\PurchaseOrderReport\PurchaseOrderReport;
use App\PurchaseOrderReport\Item as ReportItem;

use App\PurchaseOrder\PurchaseOrder;
use App\PurchaseOrder\Item as PurchaseItem;


use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\PurchaseOrderReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
class PurchaseOrderReportController extends Controller
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
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'user_id' => '',
            'product_id' => '',
            'vendor_id' => '',
            'uom_id' => '',
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
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'vendor_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new PurchaseOrderReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
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
          
          $product_id = $request->product_id;
          $vendor_id = $request->vendor_id;
          $purchase_id = $request->purchase_order_id;

          if($product_id > 0){
            $product_id = $product_id;
            $product_op = "=";
          }else{
            $product_id = null;
            $product_op = "!=";
          }

          if($vendor_id > 0){
            $vendor_id = $vendor_id;
            $vendor_op = "=";
          }else{
            $vendor_id = null;
            $vendor_op = "!=";
          }

          $productsItem = PurchaseItem::join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
                  ->where('product_id',$product_op,$product_id)
                  ->where('purchase_orders.vendor_id',$vendor_op,$vendor_id)
                  ->where('purchase_orders.date', '>=',$from_date)
                  ->where('purchase_orders.date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->purchase_date = $reportX ->date;
                        $PR = PurchaseOrder::where('id','=',$reportX->purchase_order_id)->get();
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
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = PurchaseOrderReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['uom','product','products','vendors','purchase_orders'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>',1)
            ->where('purchase_date','>=',$RequestID->from_date)
            ->where('purchase_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.purchase_orders_report',compact('reports'));
           return $pdf->download(now().'purchase_orders_report.pdf');

           return redirect()->back();
        return redirect()->back();
             // return view('docs.purchaser',compact('reports'));
        }
    }
    }

    public function excel()
    { 
        $id = PurchaseOrderReport::latest()->take(1)->value('id');
        return Excel::download(new PurchaseOrderReportExcel($id), now().'purchase_orders_report.xlsx');
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
        $user = auth()->user();
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'vendor_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new PurchaseOrderReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
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
          
         

        // $warehouseN = Warehouse::where('id','=',$request->warehouse_id)->get();
            if ($request->product_id > 0){
                $productsItem = PurchaseItem::where('product_id','=',$request->product_id)
                //   ->orwhere('vendor_id','=',$request->vendor_id)
                  ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
                  ->where('purchase_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('purchase_orders.date', '<=',$request->input('to_date').' 00:00:00')
                //   inner join with purchase request date
                //   ->orwhere('date','>=',$request->input('from_date').' 01:00:00')
                //   ->orwhere('date','<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->purchase_date = $reportX ->date;
                        $PR = PurchaseOrder::where('id','=',$reportX->purchase_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->vendor_id > 0){
                $productsItem = PurchaseItem::where('vendor_id','=',$request->vendor_id)
                  ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
                  ->where('purchase_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('purchase_orders.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->purchase_date = $reportX ->date;
                        $PR = PurchaseOrder::where('id','=',$reportX->purchase_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }else{
                $productsItem = PurchaseItem::join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
                ->where('purchase_orders.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('purchase_orders.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                      $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                      $reportItem ->product_id = $reportX->product_id;
                      $reportItem ->vendor_id = $reportX->vendor_id;
                      $reportItem ->qty = $reportX->qty;
                      $reportItem ->qty_received = $reportX->qty_received;
                      $reportItem ->unit_price = $reportX->unit_price;
                      $reportItem ->uom_id = $reportX->uom_id;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->purchase_date = $reportX ->date;
                      $PR = PurchaseOrder::where('id','=',$reportX->purchase_order_id)->get();
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
