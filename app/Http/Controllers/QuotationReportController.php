<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuotationReport\QuotationReport;
use App\QuotationReport\Item as ReportItem;

use App\Quotation\Quotation;
use App\Quotation\Item as PurchaseItem;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\QuotationReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class QuotationReportController extends Controller
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
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
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
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
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
          $report = new QuotationReport;
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
          
          $product_id = $request->product_id;
          $client_id = $request->client_id;
          if($product_id > 0){
            $product_id = $product_id;
            $product_op = "=";
          }else{
            $product_id = null;
            $product_op = "!=";
          }

          if($client_id > 0){
            $client_id = $client_id;
            $client_op = "=";
          }else{
            $client_id = null;
            $client_op = "!=";
          }

        $productsItem = PurchaseItem::where('product_id',$product_op,$product_id)
                  ->where('client_id',$client_op,$client_id)
                  ->join('quotations', 'quotations.id', '=', 'quotation_items.quotation_id')
                  ->where('quotations.date', '>=',$from_date)
                  ->where('quotations.date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> quotation_id = $reportX->quotation_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom = $reportX->uom_unit;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->quotation_date = $reportX ->date;
                        $PR = Quotation::where('id','=',$reportX->quotation_id)->get();
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
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = QuotationReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['quotations','uom','product','products','clients'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>',2)
            ->where('quotation_date','>=',$RequestID->from_date)
            ->where('quotation_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.quotations_report',compact('reports'));
           return $pdf->download(now().'quotations.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
    }
    }

    public function excel()
    { 
        $id = QuotationReport::latest()->take(1)->value('id');
        return Excel::download(new QuotationReportExcel($id), now().'quotations.xlsx');
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
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
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
          $report = new QuotationReport;
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
          
         

        // $warehouseN = Warehouse::where('id','=',$request->warehouse_id)->get();
            if ($request->product_id > 0){
                $productsItem = PurchaseItem::where('product_id','=',$request->product_id)
                //   ->orwhere('client_id','=',$request->client_id)
                  ->join('quotations', 'quotations.id', '=', 'quotation_items.quotation_id')
                  ->where('quotations.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('quotations.date', '<=',$request->input('to_date').' 00:00:00')
                //   inner join with purchase request date
                //   ->orwhere('date','>=',$request->input('from_date').' 01:00:00')
                //   ->orwhere('date','<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> quotation_id = $reportX->quotation_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->quotation_date = $reportX ->date;
                        $PR = Quotation::where('id','=',$reportX->quotation_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->client_id > 0){
                $productsItem = PurchaseItem::where('client_id','=',$request->client_id)
                  ->join('quotations', 'quotations.id', '=', 'quotation_items.quotation_id')
                  ->where('quotations.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('quotations.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> quotation_id = $reportX->quotation_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->quotation_date = $reportX ->date;
                        $PR = Quotation::where('id','=',$reportX->quotation_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = PurchaseItem::join('quotations', 'quotations.id', '=', 'quotation_items.quotation_id')
                ->where('quotations.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('quotations.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                      $reportItem -> quotation_id = $reportX->quotation_id;
                      $reportItem ->product_id = $reportX->product_id;
                      $reportItem ->client_id = $reportX->client_id;
                      $reportItem ->qty = $reportX->qty;
                      $reportItem ->qty_received = $reportX->qty_received;
                      $reportItem ->unit_price = $reportX->unit_price;
                      $reportItem ->uom_id = $reportX->uom_id; 
                      $reportItem ->total = $reportX->total;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->quotation_date = $reportX ->date;
                      $PR = Quotation::where('id','=',$reportX->quotation_id)->get();
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
