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
use App\Product\Item;
use App\Uom;
use App\WarehouseReportCriteria\WarehouseReportCriteria;
use App\WarehouseReportCriteria\Item as ReportItem;

class WarehousesReportCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return api([
            'data' => Warehouse::with(['items.product', 'vendor','uom'])->search()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = array_merge([
            'user_id' => '',
            'warehouse_id' => '',
            'category_id' => '',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable',
            'warehouse_id' => 'nullable',
            'category_id' => 'nullable',
            'product_id' => 'nullable',
            'vendor_id' => 'nullable',
            'uom_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);

          // $inputs = $request->all();
          $report = new WarehouseReportCriteria;
          $report ->user_id = 1;
          $report ->warehouse_id = $request->input('warehouse_id');
          $report ->category_id = $request->input('category_id');
          $report ->product_id = $request->input('product_id');
          $report ->vendor_id = $request->input('vendor_id');
          $report ->uom_id = $request->input('uom_id');
          $report ->from_date = $request->input('from_date');
          $report ->to_date = $request->input('to_date');
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save();  
          
          $productsItem = ProductItem::where('warehouse_id','=',$request->warehouse_id)
          ->orwhere('category_id','=',$request->category_id)
          ->orwhere('vendor_id','=',$request->vendor_id)
          ->orwhere('product_id','=',$request->product_id)
          ->get();

        // $warehouseN = Warehouse::where('id','=',$request->warehouse_id)->get();
          if ($request->product_id > 0){
                $reportItem = new ReportItem;
                $reportItem -> report_id = $report->id;
                $reportItem ->warehouse_id = $request->input('warehouse_id');
                $reportItem ->category_id = $request->input('category_id');
                $reportItem ->product_id = $request->product_id;
                $reportItem ->vendor_id = $request->input('vendor_id');
                $reportItem ->uom_id = $request->input('uom_id');
                $reportItem ->from_date = $request->input('from_date');
                $reportItem ->to_date = $request->input('to_date');
                $reportItem->save();   
          }else{
            foreach ($productsItem as $reportX)
            {
                $reportItem = new ReportItem;
                $reportItem -> report_id = $report->id;
                $reportItem ->warehouse_id = $request->input('warehouse_id');
                $reportItem ->category_id = $request->input('category_id');
                $reportItem ->product_id = $reportX->product_id;
                $reportItem ->vendor_id = $request->input('vendor_id');
                $reportItem ->uom_id = $request->input('uom_id');
                $reportItem ->from_date = $request->input('from_date');
                $reportItem ->to_date = $request->input('to_date');
                $reportItem->save();   
            }
          }

          return api([
            'saved' => true,
            'id' => $report->id
        ]);
        //   $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('warehouse.report');
        //   return $pdf->download('warehousesreportcriteria.pdf');
      
        

    }

    public function pdf()
    {
        $id = WarehouseReportCriteria::latest()->take(1)->get();
        foreach ($id as $RequestID){
            $reports = ReportItem::with(['warehouse', 'category','uom','product','products','vendor'])
            ->where('report_id','=',$RequestID->id)
            ->where('category_id','=',$RequestID->category_id)
            ->where('vendor_id','=',$RequestID->vendor_id)
            ->where('warehouse_id','=',$RequestID->warehouse_id)
            ->where('created_at','>=',$RequestID->from_date)
            ->where('created_at','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'landscape')->setWarnings(false)->loadView('warehouse.report',compact('reports'));
            // return $pdf->download('warehousesreportcriteria.pdf');
            return view('warehouse.report',compact('reports'));
        }
        
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
