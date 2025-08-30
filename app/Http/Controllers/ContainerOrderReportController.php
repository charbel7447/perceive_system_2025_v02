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
use App\ContainerOrderReport\ContainerOrderReport;
use App\ContainerOrderReport\Item as ReportItem;

use App\ContainerOrder\ContainerOrder;
use App\ContainerOrder\Item as PurchaseItem;


use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

class ContainerOrderReportController extends Controller
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
          $report = new ContainerOrderReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->shipper_id = $request->input('shipper_id');
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
          $shipper_id = $request->shipper_id;
          $purchase_id = $request->purchase_order_id;

          if($product_id > 0){
            $product_id = $product_id;
            $product_op = "=";
          }else{
            $product_id = null;
            $product_op = "!=";
          }

          if($shipper_id > 0){
            $shipper_id = $shipper_id;
            $shipper_op = "=";
          }else{
            $shipper_id = null;
            $shipper_op = "!=";
          }

          $productsItem = PurchaseItem::join('container_orders', 'container_orders.id', '=', 'container_order_items.container_order_id')
                  ->where('product_id',$product_op,$product_id)
                  ->where('container_orders.shipper_id',$shipper_op,$shipper_id)
                  ->where('container_orders.date', '>=',$from_date)
                  ->where('container_orders.date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> container_order_id = $reportX->container_order_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->shipper_id = $reportX->shipper_id;
                        $reportItem ->qty = $reportX->quantity;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->container_date = $reportX ->date;
                        $PR = ContainerOrder::where('id','=',$reportX->container_order_id)->get();
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
        $ReportId1 = ContainerOrderReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['uom','product','products','shippers','container_orders'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>',1)
            ->where('container_date','>=',$RequestID->from_date)
            ->where('container_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a4', 'landscape')->setWarnings(false)->loadView('docs.container_orders_report',compact('reports'));
            return $pdf->download(now().'container_orders_report.pdf');

           //return view('docs.container_orders_report',compact('reports'));

           return redirect()->back();
        return redirect()->back();
             // return view('docs.purchaser',compact('reports'));
        }
    }
    }

    public function excel()
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = ContainerOrderReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports1 = ReportItem::join('products','products.id','=','product_id')
            ->join('container_orders','container_orders.id','=','container_order_id')
            ->join('shippers','shippers.id','=','container_order_report_items.shipper_id')
            // ->join('uom','uom.id','=','container_order_report_items.uom_id')
            ->join('users','users.id','=','container_orders.user_id')
            ->select(
            'container_orders.number as CO-Number','shippers.person as Shipper Name',
            'products.code as ItemCode','products.description as ItemName',
            'container_order_report_items.qty','container_order_report_items.unit_price','uom',
            'container_orders.date as Request Date','container_orders.created_by as created_by',
           
            )
            ->where('report_id','=',$RequestID->id)
            ->where('container_orders.status_id','>',1)
            ->get();
           
            $header_style = (new StyleBuilder())->setCellAlignment('center')->setFontBold()->setBackgroundColor("EDEDED")->build();

            $rows_style = (new StyleBuilder())
                ->setFontSize(15)
                ->setShouldWrapText()
                ->setBackgroundColor("EDEDED")
                ->build();

            // (new FastExcel($reports1))->download(now().'purchase_requests.xlsx');
            return (new FastExcel($reports1))
            ->headerStyle($header_style)
            // ->rowsStyle($rows_style)
            ->download(now().'container_orders_report.xlsx');
         
        }
    }
             // return view('docs.purchaser',compact('reports'));
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
          $report = new ContainerOrderReport;
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
