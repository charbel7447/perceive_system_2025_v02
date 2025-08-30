<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SellerPaymentsReport\SellerPaymentsReport;
use App\SellerPaymentsReport\Item as ReportItem;

use App\SellerPaymentDocs\SellerPaymentDocs;
use App\SellerPaymentDocs\Item;
use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

class SellerPaymentReportController extends Controller
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
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
          $report = new SellerPaymentsReport;
          $report ->user_id = $username;
          $report ->client_id = $request->input('client_id');
          $report ->seller_id = $request->input('seller_id');
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
           
          $client_id = $request->client_id;

          if($client_id > 0){
            $client_id = $client_id;
            $client_op = "=";
          }else{
            $client_id = null;
            $client_op = "!=";
          }

          $seller_id = $request->seller_id;

          if($seller_id > 0){
            $seller_id = $seller_id;
            $seller_op = "=";
          }else{
            $seller_id = null;
            $seller_op = "!=";
          }

          $productsItem = Item::where('seller_payment_id',$seller_op,$seller_id)
          ->where('client_id',$client_op,$client_id)
          ->whereBetween('payment_date',[$from_date, $to_date])
          ->get();

          $report ->created_by = $username;
          $report ->body = $productsItem;
          $report->save(); 

          foreach ($productsItem as $reportX)
          {
              $reportItem = new ReportItem;
              $reportItem -> report_id = $report->id;
              $reportItem -> seller_payment_id = $reportX->id;
              $reportItem ->sales_order_id = $reportX->sales_order_id;
              $reportItem ->client_id = $reportX->client_id;
              $reportItem ->seller_id = $reportX->seller_payment_id;
              $reportItem ->payment_mode = $reportX->payment_mode;
              $reportItem ->amount_received = $reportX->amount_received;
              $reportItem ->payment_date = $reportX->payment_date;
              $reportItem ->from_date = $report ->from_date;
              $reportItem ->to_date = $report ->to_date;
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = SellerPaymentsReport::latest()->take(1)->value('id');
        $reports = ReportItem::with(['clients','seller_payments'])
            ->where('report_id','=',$ReportId1)
            ->orderBy('client_id','desc')
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.seller_payments_report',compact('reports'));
           return $pdf->download(now().'seller_payments_report.pdf');
          // return view('docs.seller_payments_report',compact('reports'));
        }
    }

    public function excel()
    {
        $user = auth()->user();
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = SellerPaymentsReport::latest()->take(1)->value('id');
        $reports = ReportItem::join('clients','seller_payments_report_items.client_id','=','clients.id')
        ->join('seller_payments_docs_items','seller_payments_report_items.seller_payment_id','=','seller_payments_docs_items.id')
        ->select(
        'clients.name as Client Name',
        'seller_payments_docs_items.number',
        'seller_payments_report_items.payment_date',
        'seller_payments_report_items.amount_received',
        )
            ->where('report_id','=',$ReportId1)
            ->orderBy('seller_payments_report_items.client_id','desc')
            ->get();

            $header_style = (new StyleBuilder())->setCellAlignment('center')->setFontBold()->setBackgroundColor("EDEDED")->build();

            $rows_style = (new StyleBuilder())
                ->setFontSize(15)
                ->setShouldWrapText()
                ->setBackgroundColor("EDEDED")
                ->build();

            // (new FastExcel($reports1))->download(now().'purchase_requests.xlsx');
            return (new FastExcel($reports))
            ->headerStyle($header_style)
            // ->rowsStyle($rows_style)
            ->download(now().'ReportId1.xlsx');
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
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
          $report = new SalesOrderReport;
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
                  ->join('sales_orders', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
                  ->where('sales_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('sales_orders.date', '<=',$request->input('to_date').' 00:00:00')
                //   inner join with purchase request date
                //   ->orwhere('date','>=',$request->input('from_date').' 01:00:00')
                //   ->orwhere('date','<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> sales_order_id = $reportX->sales_order_id;
                        $reportItem ->product_id = $reportX->item_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->quantity;
                        $reportItem ->unit_price = $reportX->price;
                        $reportItem ->uom = $reportX->uom;
                        $reportItem ->total = $reportX->quantity * $reportX->price;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->sales_order_date = $reportX ->date;
                        $PR = SalesOrder::where('id','=',$reportX->sales_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }elseif ($request->client_id > 0){
                $productsItem = PurchaseItem::where('client_id','=',$request->client_id)
                  ->join('sales_orders', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
                  ->where('sales_orders.date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('sales_orders.date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> sales_order_id = $reportX->sales_order_id;
                        $reportItem ->product_id = $reportX->item_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->qty = $reportX->quantity;
                        $reportItem ->unit_price = $reportX->price;
                        $reportItem ->uom = $reportX->uom;
                        $reportItem ->total = $reportX->quantity * $reportX->price;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->sales_order_date = $reportX ->date;
                        $PR = SalesOrder::where('id','=',$reportX->sales_order_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = PurchaseItem::join('sales_orders', 'sales_orders.id', '=', 'sales_order_items.sales_order_id')
                ->where('sales_orders.date', '>=',$request->input('from_date').' 01:00:00')
                ->where('sales_orders.date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                      $reportItem -> sales_order_id = $reportX->sales_order_id;
                      $reportItem ->product_id = $reportX->item_id;
                      $reportItem ->client_id = $reportX->client_id;
                      $reportItem ->qty = $reportX->quantity;
                      $reportItem ->qty_received = $reportX->qty_received;
                      $reportItem ->unit_price = $reportX->price;
                      $reportItem ->uom = $reportX->uom; 
                      $reportItem ->total = $reportX->quantity * $reportX->price;
                      $reportItem ->from_date = $report ->from_date;
                      $reportItem ->to_date = $report ->to_date;
                      $reportItem ->sales_order_date = $reportX ->date;
                      $PR = SalesOrder::where('id','=',$reportX->sales_order_id)->get();
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
