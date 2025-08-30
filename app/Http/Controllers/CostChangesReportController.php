<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use DB;
use Auth;
use PDF;
use App\Product\Product as ProductItem;
use App\Product\Items;
use App\Uom;

use App\PriceChanges as PurchaseItem;

use App\PriceChangeReport\PriceChangeReport;
use App\PriceChangeReport\Item as ReportItem;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\CostChange as ExcelCostChange;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class CostChangesReportController extends Controller
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
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'user_id' => '',
            'product_id' => '',
            'purchase_order_id' => '',
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
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'vendor_id' => 'nullable',
            'purchase_order_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new PriceChangeReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
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

          if($product_id > 0){
            $product_id = $product_id;
            $product_op = "=";
          }else{
            $product_id = null;
            $product_op = "!=";
          }


          $productsItem = PurchaseItem::where('product_id',$product_op,$product_id)
                  ->where('date', '>=',$from_date)
                  ->where('date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        if($reportX->sale_price > 0){
                            //sale_price is cost
                            $reportItem = new ReportItem;
                            $reportItem->report_id = $report->id;
                            $reportItem->product_id = $reportX->product_id;
                            $reportItem->product_name = $reportX->product_name;
                            $reportItem->unit_price = $reportX->unit_price;
                            $reportItem->unitprice = $reportX->unitprice;
                            $reportItem->sale_price = $reportX->sale_price;
                            $reportItem->class_a_price = $reportX->class_a_price;
                            $reportItem->class_b_price = $reportX->class_b_price;
                            $reportItem->class_c_price = $reportX->class_c_price;
                            $reportItem->nb_boxes_1 = $reportX->nb_boxes_1;
                            $reportItem->nb_boxes_1_price = $reportX->nb_boxes_1_price;
                            $reportItem->nb_boxes_2 = $reportX->nb_boxes_2;
                            $reportItem->nb_boxes_2_price = $reportX->nb_boxes_2_price;
                            $reportItem->nb_boxes_3 = $reportX->nb_boxes_3;
                            $reportItem->nb_boxes_3_price = $reportX->nb_boxes_3_price;
                            $reportItem->date = $reportX->date;
                            $reportItem->time = $reportX->time;
                            $reportItem->save();   
                        }
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
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = PriceChangeReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['product','products'])
            ->where('report_id','=',$RequestID->id)
            ->where('date','>=',$RequestID->from_date)
            ->where('date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.cost_changes_report',compact('reports'));
           return $pdf->download(now().'cost_changes.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
      }
    }

    public function excel()
    { 
        $id = \App\PriceChangeReport\PriceChangeReport::latest()->take(1)->value('id');
        return Excel::download(new ExcelCostChange($id), now().'cost_changes.xlsx');
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
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'vendor_id' => 'nullable',
            'purchase_order_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new PriceChangeReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->vendor_id = $request->input('vendor_id');
          $report ->purchase_order_id = $request->input('purchase_order_id');
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
                //   ->orwhere('purchase_order_item_id','=',$request->purchase_order_item_id)
                  ->join('receive_orders', 'receive_orders.id', '=', 'receive_order_items.receive_order_id')
                  ->where('receive_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('receive_orders.date', '<=',$request->input('to_date').' 00:00:00')
                //   inner join with purchase request date
                //   ->orwhere('date','>=',$request->input('from_date').' 01:00:00')
                //   ->orwhere('date','<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> receive_order_id = $reportX->receive_order_id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->purchase_order_item_id = $reportX->purchase_order_item_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->receive_order_date = $reportX ->date;
                        $PR = ReceiveOrder::where('id','=',$reportX->purchase_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->vendor_id > 0){
                $productsItem = PurchaseItem::where('vendor_id','=',$request->vendor_id)
                  ->join('receive_orders', 'receive_orders.id', '=', 'receive_order_items.receive_order_id')
                  ->where('receive_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('receive_orders.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem -> receive_order_id = $reportX->receive_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->purchase_order_item_id = $reportX->purchase_order_item_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->receive_order_date = $reportX ->date;
                        $PR = ReceiveOrder::where('id','=',$reportX->purchase_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->purchase_order_id > 0){
                $productsItem = PurchaseItem::where('purchase_order_id','=',$request->purchase_order_id)
                  ->join('receive_orders', 'receive_orders.id', '=', 'receive_order_items.receive_order_id')
                  ->where('receive_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('receive_orders.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> receive_order_id = $reportX->receive_order_id;
                        $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->vendor_id = $reportX->vendor_id;
                        $reportItem ->purchase_order_item_id = $reportX->purchase_order_item_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->receive_order_date = $reportX ->date;
                        $PR = ReceiveOrder::where('id','=',$reportX->purchase_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }else{
                $productsItem = PurchaseItem::join('receive_orders', 'receive_orders.id', '=', 'receive_order_items.receive_order_id')
                ->where('receive_orders.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('receive_orders.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                      $reportItem -> receive_order_id = $reportX->receive_order_id;
                      $reportItem -> purchase_order_id = $reportX->purchase_order_id;
                      $reportItem ->product_id = $reportX->product_id;
                      $reportItem ->vendor_id = $reportX->vendor_id;
                      $reportItem ->purchase_order_item_id = $reportX->purchase_order_item_id;
                      $reportItem ->qty = $reportX->qty;
                      $reportItem ->uom_id = $reportX->uom_id;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->receive_order_date = $reportX ->date;
                      $PR = ReceiveOrder::where('id','=',$reportX->purchase_order_id)->get();
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
