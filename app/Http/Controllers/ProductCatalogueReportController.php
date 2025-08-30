<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Category;
use App\Vendor;
use DB;
use Auth;
use PDF;
use App\Product\Product;
use App\Product\Items;
use App\Uom;

use App\ProductReport\ProductReport;
use App\ProductReport\Item as ReportItem;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class ProductCatalogueReportController extends Controller
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
        ProductReport::truncate();
        ReportItem::truncate();
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
          $report = new ProductReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->vendor_id = $request->input('vendor_id');
          $report ->from_code = $request->input('from_code');
          $report ->to_code = $request->input('to_code');
          $report ->subcategory_id = $request->input('subcategory_id');
          $report ->category_id = $request->input('category_id');
          $report ->from_qty = $request->input('from_qty');
          $report ->to_qty = $request->input('to_qty');
          $report ->from_p_price = $request->input('from_p_price');
          $report ->to_p_price = $request->input('to_p_price');
          $report ->from_price = $request->input('from_price');
          $report ->to_price = $request->input('to_price');

          

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
          $from_code = $request->from_code;
          $to_code = $request->to_code;
          $category_id = $request->category_id;
          $subcategory_id = $request->subcategory_id;
          $from_qty = $request->from_qty;
          $to_qty = $request->to_qty;
          $from_p_price = $request->from_p_price;
          $from_price = $request->from_price;
          $to_p_price = $request->to_p_price;
          $to_price = $request->to_price;

          $size = $request->size;
          $location = $request->location;
          $status = $request->status;
          $profit = $request->profit;
          
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

          if($from_code > 0){
            $from_code = $from_code;
            $from_code_op = ">=";
          }else{
            $from_code = null;
            $from_code_op = "!=";
          }

          if($to_code > 0){
            $to_code = $to_code;
            $to_code_op = "<=";
          }else{
            $to_code = null;
            $to_code_op = "!=";
          }

          if($category_id > 0){
            $category_id = $category_id;
            $category_op = "=";
          }else{
            $category_id = null;
            $category_op = "!=";
          }

          if($subcategory_id > 0){
            $subcategory_id = $subcategory_id;
            $subcategory_op = "=";
          }else{
            $subcategory_id = null;
            $subcategory_op = "!=";
          }

          if($from_qty > 0){
            $from_qty = $from_qty;
            $from_qty_op = ">=";
          }else{
            $from_qty = null;
            $from_qty_op = "!=";
          }

          if($to_qty > 0){
            $to_qty = $to_qty;
            $to_qty_op = "<=";
          }else{
            $to_qty = null;
            $to_qty_op = "!=";
          }

          if($from_p_price > 0){
            $from_p_price = $from_p_price;
            $from_p_price_op = ">=";
          }else{
            $from_p_price = null;
            $from_p_price_op = "!=";
          }

          if($to_p_price > 0){
            $to_p_price = $to_p_price;
            $to_p_price_op = "<=";
          }else{
            $to_p_price = null;
            $to_p_price_op = "!=";
          }

          if($to_price > 0){
            $to_price = $to_price;
            $to_price_op = "<=";
          }else{
            $to_price = null;
            $to_price_op = "!=";
          }

          if($from_price > 0){
            $from_price = $from_price;
            $from_price_op = ">=";
          }else{
            $from_price = null;
            $from_price_op = "!=";
          }

          if($location > 0){
            $location = $location;
            $location_op = "LIKE";
          }else{
            $location = 'kabbouchi';
            $location_op = "NOT LIKE";
          }

          if($size > 0){
            $size = $size;
            $size_op = "=";
          }else{
            $size = 555555;
            $size_op = "!=";
          }

          if($profit > 0){
            $profit = $profit;
            $profit_op = "=";
          }else{
            $profit = 555555;
            $profit_op = "!=";
          }

          

          if($status == 1){
            $status = 'publish';
            $status_op = "=";
          }else if($status == 2){
            $status = 'disabled';
            $status_op = "=";
          }else{
            $status = 'any';
            $status_op = "!=";
          }
          $productsItem = Product::where('id',$product_op,$product_id)
                  ->where('code',$from_code_op,$from_code)
                  ->where('code',$to_code_op,$to_code)
                  ->where('vendor_id',$vendor_op,$vendor_id)
                  ->where('category_id',$category_op,$category_id)
                  ->where('sub_categoryid',$subcategory_op,$subcategory_id)
                  ->where('current_stock',$from_qty_op,$from_qty)
                  ->where('current_stock',$to_qty_op,$to_qty)
                  ->where('price',$from_p_price_op,$from_p_price)
                  ->where('price',$to_p_price_op,$to_p_price)
                  ->where('sale_price',$from_price_op,$from_price)
                  ->where('sale_price',$to_price_op,$to_price)

                  ->where('rating_value',$profit_op,$profit)
                  ->where('location',$location_op,'%'.$location.'%')
                  ->where('size',$size_op,$size)
                  ->where('status',$status_op,$status)

                //   ->where('date', '>=',$from_date)
                //   ->where('date', '<=',$to_date)
                  ->get();
             

                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->product_id = $reportX->id;
                        $reportItem ->product_name = $reportX->title;
                        $reportItem ->code = $reportX->code;
                        $reportItem ->thumbnail = $reportX->thumbnail ?? 'placeholder.png';
                        if($reportX->thumbnail == null){
                          $reportItem ->thumbnail = 'placeholder.png';
                        }
                        // $new_code = implode('', str_split(sprintf('%09d', $id), 3));
                        $barcode = \DNS1D::getBarcodePNG($reportX->code, 'C39+',1.2,33);
                        $reportItem ->barcode = $barcode;
                        // $reportItem ->barcode = $reportX->barcode;
                        $reportItem ->vendor_id = $reportX->vendor_id;

                        $reportItem ->category_id = $reportX->category_id;
                        $reportItem ->subcategory_id = $reportX->sub_categoryid;
                        $reportItem ->category_name = \App\Category::where('id','=',$reportX->category_id)->value('name');
                        $reportItem ->subcategory_name = \App\SubCategory::where('id','=',$reportX->sub_categoryid)->value('name');
                        $reportItem ->uom = $reportX->uom;

                        $reportItem ->vendor_name = \App\Vendor::where('id','=',$reportX->vendor_id)->value('company');
                        $reportItem ->current_stock = $reportX->current_stock;
                        $reportItem ->price = $reportX->price;
                        $reportItem ->unitprice = $reportX->unitprice;
                        $reportItem ->sale_price = $reportX->sale_price;
                        $reportItem ->on_hold_qty = $reportX ->on_hold_qty;
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
        $requestid = ProductReport::latest()->take(1)->latest()->value('id');
        
        //  $data =  ReportItem::where('report_id','=',$requestid)
        //     ->orderby('category_name','asc')->orderby('subcategory_name','asc')->orderby('product_name','asc')->get();

        // $doc  = 'docs.products_catalogue_landscape_2';

        // $model = $data;
 
      //return view('docs.products_catalogue_landscape_2',compact('model'));
        // return pdfCatalogueLandscape($doc, $data);
        
        // $user = auth()->user();
        // if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
        //     return response()->json(['error' => 'Forbidden.'], 403);
        // }else{
        // $ReportId1 = ProductReport::latest()->take(1)->get();
        // foreach ($ReportId1 as $RequestID){
        //     $reports = ReportItem::where('report_id','=',$RequestID->id)
        //     ->get();
        //     //   $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
        //   //    ->setPaper('a4', 'landscape')->setWarnings(false)->loadView('docs.products_catalogue',compact('reports'));
        //  //  return $pdf->download(now().'products_catalogue.pdf');
        //      return view('docs.products_catalogue',compact('reports'));

          $items = ReportItem::where('report_id','=',$requestid)
              ->orderby('category_name','asc')->orderby('subcategory_name','asc')->orderby('product_name','asc')->get();

          foreach ($items as $item) {
              // Parse uom JSON if needed
              // $item->uom = is_string($item->uom) ? json_decode($item->uom) : $item->uom;

              // Add current stock (you may calculate or fetch it)
              $item->current_stock = $item->current_stock ?? 0;

              // Convert image to base64 if exists
              $path = storage_path('app/uploads/' . $item->thumbnail);
              $item->thumbnail_base64 = file_exists($path)
                  ? 'data:image/png;base64,' . base64_encode(file_get_contents($path))
                  : null;

              // Category name fallback
              $item->category_name = $item->category_name ?? 'N/A';
          }

          $pdf = new Mpdf([
              'format' => 'A4-L',     // 'A4-L' means A4 landscape
              'margin_left' => 5,
              'margin_right' => 5,
              'margin_top' => 5,
              'margin_bottom' => 5,
              'margin_header' => 0,
              'margin_footer' => 0,
          ]);
          $html = view('docs.products_catalogue_landscape_2', compact('items'))->render();
          $pdf->WriteHTML($html);

          return $pdf->Output('product_labels.pdf', 'I');
    
           return redirect()->back();
        return redirect()->back();
             
    //     }
    // }
    }


    public function portrait_pdf()
    {
           $requestid = ProductReport::latest()->take(1)->latest()->value('id');
        
        //  $data =  ReportItem::where('report_id','=',$requestid)
        //     ->get();

//  $data =  ReportItem::where('report_id','=',$requestid)
//             ->orderby('category_name','asc')->orderby('subcategory_name','asc')->orderby('product_name','asc')->get();
            
//         $doc  = 'docs.products_catalogue_portrait_2';

 

//         return pdfCataloguePortrait($doc, $data);
        
        // $user = auth()->user();
        // if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
        //     return response()->json(['error' => 'Forbidden.'], 403);
        // }else{
        // $ReportId1 = ProductReport::latest()->take(1)->get();
        // foreach ($ReportId1 as $RequestID){
        //     $reports = ReportItem::where('report_id','=',$RequestID->id)
        //     ->get();
        //      //  $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
        //     //  ->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.products_catalogue_portrait',compact('reports'));
        // //   return $pdf->download(now().'products_catalogue_portrait.pdf');
        // return view('docs.products_catalogue_portrait',compact('reports'));
        //   return redirect()->back();
        // return redirect()->back();
             
        // }
        // }
          $items = ReportItem::where('report_id','=',$requestid)
              ->orderby('category_name','asc')->orderby('subcategory_name','asc')->orderby('product_name','asc')->get();

          foreach ($items as $item) {
              // Parse uom JSON if needed
              // $item->uom = is_string($item->uom) ? json_decode($item->uom) : $item->uom;

              // Add current stock (you may calculate or fetch it)
              $item->current_stock = $item->current_stock ?? 0;

              // Convert image to base64 if exists
              $path = storage_path('app/uploads/' . $item->thumbnail);
              $item->thumbnail_base64 = file_exists($path)
                  ? 'data:image/png;base64,' . base64_encode(file_get_contents($path))
                  : null;

              // Category name fallback
              $item->category_name = $item->category_name ?? 'N/A';
          }

          $pdf = new Mpdf([
              'format' => 'A4-P',     // 'A4-L' means A4 landscape
              'margin_left' => 5,
              'margin_right' => 5,
              'margin_top' => 5,
              'margin_bottom' => 5,
              'margin_header' => 0,
              'margin_footer' => 0,
          ]);
          $html = view('docs.products_catalogue_portrait_2', compact('items'))->render();
          $pdf->WriteHTML($html);

          return $pdf->Output('product_labels.pdf', 'I');
    
           return redirect()->back();
        return redirect()->back();
    }
    

    public function excel()
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = ProductReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports1 = ReportItem::select(
            'code as ItemCode','vendor_name as Supplier Name','product_name as ItemName',
            'category_name as Category','subcategory_name as SubCategory','uom as U.O.M',
            'price','sale_price',
            'on_hold_qty','current_stock'
            )
            ->where('report_id','=',$RequestID->id)
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
            ->download(now().'products_report.xlsx');
         
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
