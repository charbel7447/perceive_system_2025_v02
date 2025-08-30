<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvoiceReport\InvoiceReport;
use App\InvoiceReport\Item as ReportItem;

use App\Invoice\Invoice;
use App\Invoice\Item as PurchaseItem;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\InvoiceReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceReportController extends Controller
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
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
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
    public function store_old(Request $request)
    {
        $user = auth()->user();
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
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
          $report = new InvoiceReport;
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

          $productsItem = PurchaseItem::where('item_id',$product_op,$product_id)
                  ->where('client_id',$client_op,$client_id)
                  ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                  ->where('invoices.date', '>=',$from_date)
                  ->where('invoices.date', '<=',$to_date)
                  ->when($request->has('field1'), function ($query) use ($request) {
                    if($request->field1 > 0){
                      $field1 = $request->field1;
                      $field1_op = "LIKE";
                    }else{
                      $field10 = 'kabbouchi';
                      $field10_op = "NOT LIKE";
                    }
                    $query->where('field1',$field1_op,'%'.$field1.'%');
                  })
                  ->when($request->has('field2'), function ($query) use ($request) {
                    if($request->field2 > 0){
                      $field2 = $request->field2;
                      $field2_op = "LIKE";
                    }else{
                      $field2 = 'kabbouchi';
                      $field2_op = "NOT LIKE";
                    }
                    $query->where('field2',$field2_op,'%'.$field2.'%');
                  })
                  ->when($request->has('field3'), function ($query) use ($request) {
                    if($request->field3 > 0){
                      $field3 = $request->field3;
                      $field3_op = "LIKE";
                    }else{
                      $field3 = 'kabbouchi';
                      $field3_op = "NOT LIKE";
                    }
                    $query->where('field3',$field3_op,'%'.$field3.'%');
                  })
                  ->when($request->has('field4'), function ($query) use ($request) {
                    if($request->field4 > 0){
                      $field4 = $request->field4;
                      $field4_op = "LIKE";
                    }else{
                      $field4 = 'kabbouchi';
                      $field4_op = "NOT LIKE";
                    }
                    $query->where('field4',$field4_op,'%'.$field4.'%');
                  })
                  ->when($request->has('field5'), function ($query) use ($request) {
                    if($request->field5 > 0){
                      $field5 = $request->field5;
                      $field5_op = "LIKE";
                    }else{
                      $field5 = 'kabbouchi';
                      $field5_op = "NOT LIKE";
                    }
                    $query->where('field5',$field5_op,'%'.$field5.'%');
                  })
                  ->when($request->has('field6'), function ($query) use ($request) {
                    if($request->field6 > 0){
                      $field6 = $request->field6;
                      $field6_op = "LIKE";
                    }else{
                      $field6 = 'kabbouchi';
                      $field6_op = "NOT LIKE";
                    }
                    $query->where('field6',$field6_op,'%'.$field6.'%');
                  })
                  ->when($request->has('field7'), function ($query) use ($request) {
                    if($request->field7 > 0){
                      $field7 = $request->field7;
                      $field7_op = "LIKE";
                    }else{
                      $field7 = 'kabbouchi';
                      $field7_op = "NOT LIKE";
                    }
                    $query->where('field7',$field7_op,'%'.$field7.'%');
                  })
                  ->when($request->has('field8'), function ($query) use ($request) {
                    if($request->field8 > 0){
                      $field8 = $request->field8;
                      $field8_op = "LIKE";
                    }else{
                      $field8 = 'kabbouchi';
                      $field8_op = "NOT LIKE";
                    }
                    $query->where('field8',$field8_op,'%'.$field8.'%');
                  })
                  ->when($request->has('field9'), function ($query) use ($request) {
                    if($request->field9 > 0){
                      $field9 = $request->field9;
                      $field9_op = "LIKE";
                    }else{
                      $field9 = 'kabbouchi';
                      $field9_op = "NOT LIKE";
                    }
                    $query->where('field9',$field9_op,'%'.$field9.'%');
                  })
                  ->when($request->has('field10'), function ($query) use ($request) {
                    if($request->field10 > 0){
                      $field10 = $request->field10;
                      $field10_op = "LIKE";
                    }else{
                      $field10 = 'kabbouchi';
                      $field10_op = "NOT LIKE";
                    }
                    $query->where('field10',$field10_op,'%'.$field10.'%');
                  })
                  ->get();
                  // DB::table('test1')
                  // ->where('id', 1)
                  // ->update(['body' => $productsItem]);
                  
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> invoice_id = $reportX->invoice_id;
                        $reportItem ->product_id = $reportX->item_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->quantity;
                        $reportItem ->unit_price = $reportX->price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->uom = $reportX->uom_code;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->invoice_date = $reportX ->date;
                        $reportItem ->status_id = Invoice::where('id','=',$reportX->invoice_id)->value('status_id');
                        $reportItem->save();   
                    } 

          return api([
            'saved' => true,
            'id' => $report->id
        ]);
    }
    
    }

public function store(Request $request)
{
    $user = auth()->user();
    if ($user->is_invoices_view == 0 && $user->is_admin != 1) {
        return response()->json(['error' => 'Forbidden.'], 403);
    } else {
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'client_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);

        $username = Auth::user()->id;
        $report = new InvoiceReport;
        $report->user_id = $username;
        $report->product_id = $request->input('product_id');
        $report->client_id = $request->input('client_id');

        $from_date = $request->input('from_date', '1990-01-01');
        $to_date = $request->input('to_date', '2030-01-01');

        $report->from_date = $from_date;
        $report->to_date = $to_date;
        $report->created_by = Auth::user()->name;
        $report->save();

        $product_id = $request->input('product_id');
        $client_id = $request->input('client_id');
        $product_op = $product_id ? '=' : '!=';
        $client_op = $client_id ? '=' : '!=';
        
        // $productsItemQuery = PurchaseItem::where('item_id', $product_op, $product_id)
        //     ->where('client_id', $client_op, $client_id)
        //     ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
        //     ->where('invoices.date', '>=', $from_date)
        //     ->where('invoices.date', '<=', $to_date);

        // foreach (range(1, 10) as $fieldNum) {
        //     $field = 'field' . $fieldNum;
        //     $productsItemQuery->when($request->has($field), function ($query) use ($request, $field) {
        //         $fieldValue = $request->input($field);
        //         $fieldOp = $fieldValue > 0 ? 'LIKE' : 'NOT LIKE';
        //         $query->where($field, $fieldOp, '%' . ($fieldValue > 0 ? $fieldValue : 'kabbouchi') . '%');
        //     });
        // }

        if($product_id > 0){
          $productsItemQuery = PurchaseItem::where('item_id', $product_op, $product_id)
            ->where('client_id', $client_op, $client_id)
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.date', '>=', $from_date)
            ->where('invoices.date', '<=', $to_date);
        }else{
        $productsItemQuery = \App\Invoice\Invoice::where('invoices.client_id', $client_op, $client_id)
            
            ->where('invoices.date', '>=', $from_date)
            ->where('invoices.date', '<=', $to_date);
        }
        foreach (range(1, 10) as $fieldNum) {
            $field = 'field' . $fieldNum;
            $productsItemQuery->when($request->has($field), function ($query) use ($request, $field) {
                $fieldValue = $request->input($field);
                $fieldOp = $fieldValue > 0 ? 'LIKE' : 'NOT LIKE';
                $query->where($field, $fieldOp, '%' . ($fieldValue > 0 ? $fieldValue : 'kabbouchi') . '%');
            });
        }
        
        $productsItemQuery->chunk(100, function ($productsItems) use ($report) {
            foreach ($productsItems as $reportX) {
                $reportItem = new ReportItem;
                $reportItem->report_id = $report->id;
                $reportItem->invoice_id = $reportX->id;
                // $reportItem->product_id = $reportX->item_id;
                $reportItem->client_id = $reportX->client_id;
                // $reportItem->qty = $reportX->quantity;
                // $reportItem->unit_price = $reportX->price;
                // $reportItem->uom_id = $reportX->uom_id;
                // $reportItem->uom = $reportX->uom_code;
                $reportItem->total = $reportX->total;
                $reportItem->from_date = $report->from_date;
                $reportItem->to_date = $report->to_date;
                $reportItem->invoice_date = $reportX->date;
                $reportItem->status_id = Invoice::where('id', $reportX->id)->value('status_id');
                $reportItem->save();
            }
        });

        return response()->json([
            'saved' => true,
            'id' => $report->id
        ]);
    }
}


    public function pdf_old()
    {
        $user = auth()->user();
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = InvoiceReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['invoices','uom','product','products','clients'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>',1)
            ->where('invoice_date','>=',$RequestID->from_date)
            ->where('invoice_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.invoices_report',compact('reports'));
           return $pdf->download(now().'invoices.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
    }
    }

public function pdf()
    {
        $user = auth()->user();
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $id = InvoiceReport::latest()->take(1)->value('id');
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.invoices_report',compact('id'));
           return $pdf->download(now().'invoices.pdf');

        }
    }
    
    public function excel()
    { 
        $id = InvoiceReport::latest()->take(1)->value('id');
        return Excel::download(new InvoiceReportExcel($id), now().'invoices.xlsx');
    }
    
    // public function excel()
    // { 
    //     $id = InvoiceReport::latest()->take(1)->value('id');
    //     return Excel::download(new InvoiceReportExcel($id), now().'invoices.xlsx');
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
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
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
          $report = new InvoiceReport;
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
                  ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                  ->where('invoices.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('invoices.date', '<=',$request->input('to_date').' 00:00:00')
                //   inner join with purchase request date
                //   ->orwhere('date','>=',$request->input('from_date').' 01:00:00')
                //   ->orwhere('date','<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> invoice_id = $reportX->invoice_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->invoice_date = $reportX ->date;
                        $PR = Invoice::where('id','=',$reportX->invoice_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->client_id > 0){
                $productsItem = PurchaseItem::where('client_id','=',$request->client_id)
                  ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                  ->where('invoices.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('invoices.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> invoice_id = $reportX->invoice_id;
                        $reportItem ->product_id = $reportX->product_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->qty;
                        $reportItem ->unit_price = $reportX->unit_price;
                        $reportItem ->uom_id = $reportX->uom_id;
                        $reportItem ->total = $reportX->total;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->invoice_date = $reportX ->date;
                        $PR = Invoice::where('id','=',$reportX->invoice_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = PurchaseItem::join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->where('invoices.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('invoices.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                      $reportItem -> invoice_id = $reportX->invoice_id;
                      $reportItem ->product_id = $reportX->product_id;
                      $reportItem ->client_id = $reportX->client_id;
                      $reportItem ->qty = $reportX->qty;
                      $reportItem ->qty_received = $reportX->qty_received;
                      $reportItem ->unit_price = $reportX->unit_price;
                      $reportItem ->uom_id = $reportX->uom_id; 
                      $reportItem ->total = $reportX->total;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->invoice_date = $reportX ->date;
                      $PR = Invoice::where('id','=',$reportX->invoice_id)->get();
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
