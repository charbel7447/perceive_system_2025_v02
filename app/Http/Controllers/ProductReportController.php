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



use App\Excel\ProductsReport as ProductReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
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
          $report = new ProductReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->vendor_id = $request->input('vendor_id');
          $report ->code = $request->input('code');
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
          

          if($request->input('status')){
            if($request->input('status') == 1){
              $status = 'publish';
              $status_op = "=";
            }else{
              $status = 'publish';
              $status_op = "!=";
            }
            
          }else{
            $status = 'perceive';
            $status_op = "!=";
          }
          
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save();  
          
          $product_id = $request->product_id;
          $vendor_id = $request->vendor_id;
          $code = $request->code;
          $category_id = $request->category_id;
          $subcategory_id = $request->subcategory_id;
          $from_qty = $request->from_qty;
          $to_qty = $request->to_qty;
          $from_p_price = $request->from_p_price;
          $from_price = $request->from_price;
          $to_p_price = $request->to_p_price;
          $to_price = $request->to_price;

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

          if($code > 0){
            $code = $code;
            $code_op = "=";
          }else{
            $code = null;
            $code_op = "!=";
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


          $productsItem = Product::leftjoin('product_items','product_items.product_id','products.id')
          ->where('products.id',$product_op,$product_id)
          ->where('code',$code_op,$code)
          // ->where('vendor_id',$vendor_op,$vendor_id)
          ->where('product_items.vendor_id', $vendor_op, $vendor_id)
          ->where('category_id',$category_op,$category_id)
          ->where('sub_categoryid',$subcategory_op,$subcategory_id)
          ->where('current_stock',$from_qty_op,$from_qty)
          ->where('current_stock',$to_qty_op,$to_qty)
          ->where('products.price',$from_p_price_op,$from_p_price)
          ->where('products.price',$to_p_price_op,$to_p_price)
          ->where('sale_price',$from_price_op,$from_price)
          ->where('sale_price',$to_price_op,$to_price)
          ->where('status',$status_op,$status)
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

          // $productsItem = Product::where('id',$product_op,$product_id)
          //         ->where('code',$code_op,$code)
          //         ->where('vendor_id',$vendor_op,$vendor_id)
          //         ->where('category_id',$category_op,$category_id)
          //         ->where('sub_categoryid',$subcategory_op,$subcategory_id)
          //         ->where('current_stock',$from_qty_op,$from_qty)
          //         ->where('current_stock',$to_qty_op,$to_qty)
          //         ->where('price',$from_p_price_op,$from_p_price)
          //         ->where('price',$to_p_price_op,$to_p_price)
          //         ->where('sale_price',$from_price_op,$from_price)
          //         ->where('sale_price',$to_price_op,$to_price)

          //         ->where('field1',$field1_op,'%'.$field1.'%')
          //         ->where('field2',$field2_op,'%'.$field2.'%')
          //         ->where('field3',$field3_op,'%'.$field3.'%')
          //         ->where('field4',$field4_op,'%'.$field4.'%')
          //         ->where('field5',$field5_op,'%'.$field5.'%')
          //         ->where('field6',$field6_op,'%'.$field6.'%')
          //         ->where('field7',$field7_op,'%'.$field7.'%')
          //         ->where('field8',$field8_op,'%'.$field8.'%')
          //         ->where('field9',$field9_op,'%'.$field9.'%')
          //         ->where('field10',$field10_op,'%'.$field10.'%')
          //       //   ->where('date', '>=',$from_date)
          //       //   ->where('date', '<=',$to_date)
          //         ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem ->report_id = $report->id;
                        $reportItem ->product_id = $reportX->id;
                        $reportItem ->product_name = $reportX->title;
                        $reportItem ->code = $reportX->code;
                        $reportItem ->vendor_id = $reportX->vendor_id;

                        $reportItem ->category_id = $reportX->category_id;
                        $reportItem ->subcategory_id = $reportX->sub_categoryid;
                        $reportItem ->category_name = \App\Category::where('id','=',$reportX->category_id)->value('name');
                        $reportItem ->subcategory_name = \App\SubCategory::where('id','=',$reportX->sub_categoryid)->value('name');
                        $reportItem ->uom = $reportX->uom;

                        $reportItem ->vendor_name = \App\Vendor::where('id','=',$reportX->vendor_id)->value('company');
                        $reportItem ->current_stock = $reportX->current_stock;
                        $reportItem ->price = $reportX->price;
                        $reportItem ->sale_price = $reportX->sale_price;
                        $reportItem ->on_hold_qty = $reportX ->on_hold_qty;

                        $reportItem ->title = $reportX ->title;
                        $reportItem ->summary = $reportX ->summary;
                        $reportItem ->description = $reportX ->description;
                        $reportItem ->category_id = $reportX ->category_id;
                        $reportItem ->sub_category_id = $reportX ->sub_category_id;
                        $reportItem ->sub_categoryid = $reportX ->sub_categoryid;
                        $reportItem ->sub_sub_categoryid = $reportX ->sub_sub_categoryid;
                        $reportItem ->sub_sub_category_id = $reportX ->sub_sub_category_id;
                        $reportItem ->product_image_gallery = $reportX ->product_image_gallery;
                        $reportItem ->price = $reportX ->price;
                        $reportItem ->unitprice = $reportX ->unitprice;
                        $reportItem ->original_price = $reportX ->original_price;
                        $reportItem ->sale_price = $reportX ->sale_price;
                        $reportItem ->special_price = $reportX ->special_price;
                        $reportItem ->tax_percentage = $reportX ->tax_percentage;
                        $reportItem ->uom = $reportX ->uom;
                        $reportItem ->status = $reportX ->status;
                        $reportItem ->field1 = $reportX ->field1;
                        $reportItem ->field2 = $reportX ->field2;
                        $reportItem ->field3 = $reportX ->field3;
                        $reportItem ->field4 = $reportX ->field4;
                        $reportItem ->field5 = $reportX ->field5;
                        $reportItem ->field6 = $reportX ->field6;
                        $reportItem ->field7 = $reportX ->field7;
                        $reportItem ->field8 = $reportX ->field8;
                        $reportItem ->field9 = $reportX ->field9;
                        $reportItem ->field10 = $reportX ->field10;
                        $reportItem ->user_id = $reportX ->user_id;
                        $reportItem ->brand_id = $reportX ->brand_id;
                        $reportItem ->product_type = $reportX ->product_type;
                        $reportItem ->code = $reportX ->code;
                        $reportItem ->new = $reportX ->new;
                        $reportItem ->featured = $reportX ->featured;
                        $reportItem ->best_selling = $reportX ->best_selling;
                        $reportItem ->deal_of_the_day = $reportX ->deal_of_the_day;
                        $reportItem ->deal_date = $reportX ->deal_date;
                        $reportItem ->minimum_order_qty = $reportX ->minimum_order_qty;
                        $reportItem ->purchase_price = $reportX ->purchase_price;
                        $reportItem ->shipping_cost = $reportX ->shipping_cost;
                        $reportItem ->unit_price = $reportX ->unit_price;
                        $reportItem ->current_stock = $reportX ->current_stock;
                        $reportItem ->on_hold_qty = $reportX ->on_hold_qty;
                        $reportItem ->volume_box = $reportX ->volume_box;
                        $reportItem ->ct_box = $reportX ->ct_box;
                        $reportItem ->weight_box = $reportX ->weight_box;
                        $reportItem ->warehouse_qty = $reportX ->warehouse_qty;
                        $reportItem ->product_rating = $reportX ->product_rating;
                        $reportItem ->rating_value = $reportX ->rating_value;
                        $reportItem ->nb_boxes_1 = $reportX ->nb_boxes_1;
                        $reportItem ->nb_boxes_1_price = $reportX ->nb_boxes_1_price;
                        $reportItem ->nb_boxes_2 = $reportX ->nb_boxes_2;
                        $reportItem ->nb_boxes_2_price = $reportX ->nb_boxes_2_price;
                        $reportItem ->nb_boxes_3 = $reportX ->nb_boxes_3;
                        $reportItem ->nb_boxes_3_price = $reportX ->nb_boxes_3_price;
                        $reportItem ->size = $reportX ->size;
                        $reportItem ->location = $reportX ->location;
                        $reportItem ->class_a_price = $reportX ->class_a_price;
                        $reportItem ->class_b_price = $reportX ->class_b_price;
                        $reportItem ->class_c_price = $reportX ->class_c_price;
                        $reportItem ->item_box = $reportX ->item_box;
                        $reportItem ->upc_number = $reportX ->upc_number;

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
        $ReportId1 = ProductReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::where('report_id','=',$RequestID->id)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a3', 'landscape')->setWarnings(false)->loadView('docs.products_report',compact('reports'));
            return $pdf->download(now().'products_report.pdf');
          //return view('docs.products_report',compact('reports'));
           return redirect()->back();
        return redirect()->back();
             
        }
    }
    }

    public function excel()
    { 
        $id = ProductReport::latest()->take(1)->value('id');
        return Excel::download(new ProductReportExcel($id), now().'products_report.xlsx');
    }

    // public function excel()
    // {
    //     $user = auth()->user();
    //     if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
    //         return response()->json(['error' => 'Forbidden.'], 403);
    //     }else{
    //     $ReportId1 = ProductReport::latest()->take(1)->get();
    //     foreach ($ReportId1 as $RequestID){
    //         $reports1 = ReportItem::select(
    //         'code as ItemCode','vendor_name as Supplier Name','product_name as ItemName',
    //         'category_name as Category','subcategory_name as SubCategory','uom as U.O.M',
    //         'price','sale_price',
    //         'on_hold_qty','current_stock'
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
    //         ->download(now().'products_report.xlsx');
         
    //     }
    // }
             // return view('docs.purchaser',compact('reports'));
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
