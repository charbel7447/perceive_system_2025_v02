<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\Product\Inventory;
use App\Product\ItemSearch as ProductItemSearch;
use DB;
use Auth;
use App\UserCategory;
use App\Category;
use App\StockMovement\StockMovement;
use App\SubCategory;
// use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use PDF;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
use App\Vendor;
use App\PriceChanges;
use Illuminate\Support\Str;

use App\Product\Lots;

use Exception;

use App\Excel\ProductsTable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    
    public function get_info(Request $request)
    {
        $query = strtolower($request->search);

        $results = Product::with('taxes', 'uom')
            ->orderBy('code')
            ->where(function ($q) use ($query) {
                $q->where('status', '=', 'publish')
                ->where(function ($inner) use ($query) {
                    $inner->where('code', '=', $query)
                            ->orWhere(DB::raw('LOWER(upc_number)'), '=', $query);
                });
            })
            ->limit(100)
            ->get([
                'id',
                'code',
                'description',
                'unit_price as price',
                'uom_id',
                'unit',
                'current_stock',
                'current_stock as qty_on_hand',
                'location',
                'class_a_price',
                'class_b_price',
                'class_c_price',
                'sale_price as cost_price'
            ]);

        if ($results->isEmpty()) {
            throw new \Exception("No Results Found!");
        }

        return api([
            'scanned_product' => $results
        ]);
}

    public function get_info_purchase(Request $request){

        // throw new \Exception ($request->vendor_id);

      
        if(request()->has('vendor_id')) {
              $results = Product::with('taxes','uom','items')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower($request->search));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->join('product_items', 'product_items.product_id', '=', 'products.id')
            ->leftjoin('product_taxes', 'products.id', '=', 'product_taxes.product_id')
            ->where('product_items.vendor_id', '=', $request->vendor_id)
            ->limit(100)
            ->where('status','=','publish')
            ->get(['product_items.price as vendor_price', 'product_items.vendor_id',
                'product_items.reference','products.id', 'code', 'description', 'unit_price as price','uom_id','unit'
                ,'current_stock','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

        }else{
            throw new \Exception ("Select Vendor First");
        }
            
            // throw new \Exception ($results);

            return api([
                'scanned_product' => $results
            ]);
    }

    public function set_products_default_vendor()
    {
        $products = DB::table('products')->get();
       //update vendors product cost
        foreach($products as $product){
            
            $checkifExist = ProductItem::where('product_id','=',$product->id)->value('id');
            if(!$checkifExist){
                $vendor = new ProductItem;
                $vendor->product_id	= $product->id;
                $vendor->vendor_id = '9999';
                $vendor->price = $product->sale_price;
                $vendor->currency_id = 1;	
                $vendor->reference= 'reference';
                $vendor->save();
            }
               
        }
    }
    
     public function update_current_stock_web()
    {
        //dd("xx");
        $products = DB::table('products')->get();
        foreach($products as $product){
            if($product->status == 'publish'){
                $current_stock = $product->current_stock;
            
                DB::table('product_inventories')
                ->where('product_id', $product->id)
                ->update(['stock_count' => $current_stock]);
            }
        }
    }
    
    public function update_original_price()
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
             $products = DB::table('products')->get();
            foreach($products as $product){
                $box_price =  DB::table('products')
                ->where('id', $product->id)->value('unit_price');

                $item_box =  DB::table('products')
                ->where('id', $product->id)->value('item_box');
                if($item_box == 0){
                    $item_box = DB::table('products')
                    ->where('id', $product->id)->value('ct_box');
                }
                if($item_box == 0){
                    $item_box = 1;
                }

                DB::table('products')
                ->where('id', $product->id)
                ->update(['unitprice' => $box_price/$item_box]);
            }
            // $products = DB::table('products')->get();
            // foreach($products as $product){
            //     $price =  DB::table('products')
            //     ->where('id', $product->id)->value('price');
            //     if($price == null){
            //          DB::table('products')
            //     ->where('id', $product->id)
            //     ->where('category_id', '=',9)
            //     ->update(['price' => 0]);
            //     }
               
            //   $sale_price =  DB::table('products')
            //     ->where('id', $product->id)->value('sale_price');
            //     if($sale_price == null){
            //          DB::table('products')
            //     ->where('id', $product->id)
            //     ->where('category_id', '=',9)
            //     ->update(['sale_price' => 0]);
            //     }
                
            //     $unit_price =  DB::table('products')
            //     ->where('id', $product->id)->value('unit_price');
            //     if($unit_price == null){
            //          DB::table('products')
            //     ->where('id', $product->id)
            //     ->where('category_id', '=',9)
            //     ->update(['unit_price' => 0]);
            //     }
                
            //     $purchase_price =  DB::table('products')
            //     ->where('id', $product->id)->value('purchase_price');
            //     if($purchase_price == null){
            //          DB::table('products')
            //     ->where('id', $product->id)
            //     ->where('category_id', '=',9)
            //     ->update(['purchase_price' => 0]);
            //     }
              
                 
                
            // }
            
            $products = DB::table('products')->get();
            foreach($products as $product){
                
                // DB::table('products')
                // ->where('id', $product->id)
                // ->where('category_id', '=',9)
                // ->update(['sub_categoryid' => '1055']);
                
                // DB::table('products')
                // ->where('id', $product->id)
                // ->where('category_id', '=',9)
                // ->update(['sub_category_id' => '["1055"]']);
                
                DB::table('products')
                ->where('id', $product->id)
                ->where('category_id', '=',9)
                ->update(['category_ids' => '[{"id":"9","position":1},{"id":"1055","position":2}]']);
                 
                
            }
        }
    }
    
     


    public function showSalesOrders($id)
    {
        $product = Product::findOrFail($id);

        // $model = $product->salesOrders()
        $model = DB::table('sales_orders')
        ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.order_id')
        ->where('sales_order_items.item_id','=',$id)
        ->orderby('sales_orders.date','desc')
        ->paginate(5);
        // ->get();
        // $model = \App\SalesOrder\SalesOrder::join('')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showPurchaseOrders($id)
    {
        $product = Product::findOrFail($id);

        // $model = $product->salesOrders()
        $model = DB::table('purchase_orders')
        ->join('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
        ->where('product_id','=',$id)
        ->orderby('date','desc')
        ->paginate(5);
        // ->get();
        // $model = \App\SalesOrder\SalesOrder::join('')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    
    public function index()
    {
       
        
               DB::table('segments')
                 ->where('id', 1)
                 ->update(['body' => url()->full()]);
                 
                 $full_url = url()->full();
             $is_Search = 0;
                 if(Str::contains($full_url, '?page=1&q=')) {
                         DB::table('segments')
                         ->where('id', 1)
                         ->update(['body' => '111']);
                         $is_Search = 1;
                    }

            
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            if($is_Search == 1){
                 return api([
                'options' => Vendor::all(),
                'data' => Product::with(['currency','category','sub_category','items.vendor','uom'])->orderby('status','desc')->search()]);
            }else{
                 return api([
                'options' => Vendor::all(),
                'data' => Product::with(['currency','category','sub_category','items.vendor','uom'])->orderby('status','desc')->search()]);
            }
       
        }
            
    }


    public function vendors_pr()
    {
        $vendors = Vendor::get();
        $vendor_Array = [];
        foreach($vendors as $vendor){
            $vendor_Array[] = ["id" => $vendor->id, "name" => $vendor->company];
        }
        return ($vendor_Array);
    }

    public function categories_pr()
    {
        $categorys = Category::get();
        $category_Array = [];
        foreach($categorys as $category){
            $category_Array[] = ["id" => $category->id, "name" => $category->name];
        }
        return ($category_Array);
    }


    public function product_dropdown_1(Request $request)
{
        $results = \App\Product\ProductDropDown1::orderBy('id')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get(['id', 'name']);

    return api([
        'results' => $results
    ]);
}

public function product_dropdown_2(Request $request)
{
        $results = \App\Product\ProductDropDown2::orderBy('id')
            ->when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get(['id', 'name']);

    return api([
        'results' => $results
    ]);
}


public function download_product_table()
{
    return Excel::download(new ProductsTable(), now().'products_table.xlsx');
}

public function product_dropdown1()
{
    $product_dropdown1 = \App\Product\ProductDropDown1::orderBy('id')
        ->get(['id', 'name']);

    $product_dropdown1_Array = [];
    foreach($product_dropdown1 as $product_dropdown1){
        $product_dropdown1_Array[] = ["name" => $product_dropdown1->name];
    }
    return ($product_dropdown1_Array);
}

public function product_dropdown2()
{
    $product_dropdown2 = \App\Product\ProductDropDown2::orderBy('id')
        ->get(['id', 'name']);

    $product_dropdown2_Array = [];
    foreach($product_dropdown2 as $product_dropdown2){
        $product_dropdown2_Array[] = ["name" => $product_dropdown2->name];
    }
    return ($product_dropdown2_Array);
}

    public function filter(Request $request)
    {
      
        $category_id = (int) filter_var($request->category, FILTER_SANITIZE_NUMBER_INT);

        if($request->vendor_id != null){
            $vendor_id = (int) filter_var($request->vendor_id, FILTER_SANITIZE_NUMBER_INT);
        }else{
            $vendor_id = null;
        }
        

    //$category_id = Category::where('name','like','%'.$request->category.'%')->value('id');

      DB::table('test1')
      ->where('id', 1)
      ->update(['body' => $category_id]);
  
      $categ_op = "=";
      if($category_id == 0){
        $categ_op = "!=";
      }
    //   $vend_op = "=";
    //   if($vendor_id == null){
    //     $vend_op = "!=";
    //     $vendor_id = 99999;
    //   }
      $name_op = "LIKE";

      $status = $request->status;
      if($status == 1){
        $status = 'publish';
        $status_op = '=';
      }else if($status == 2){
        $status = 'disabled';
        $status_op = '=';
      }else{
        $status = 'kabbouchi';
        $status_op = '!=';
      }

        $category_id       = $category_id;
        $vendor_id         = $vendor_id;
        $status            = $request->status;
        $product_dropdown1 = $request->product_dropdown1;
        $product_dropdown2 = $request->product_dropdown2;
        $quantity_greater  = $request->quantity_greater;
        $price_min         = $request->price_min;
        $price_max         = $request->price_max;

        // throw new \Exception("xx");
        // Start building the query
        $query = Product::with(['currency', 'category', 'sub_category', 'items.vendor', 'uom'])
            ->leftJoin('product_items', 'product_items.product_id', '=', 'products.id')
            ->select('products.*', 'product_items.vendor_id');
            // ->orderBy('products.created_at', 'desc');

        // Apply filters only if request parameters are present
        if ($request->product_name != 0) {
            $query->where(function ($q) use ($request) {
                $q->where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower($request->product_name) . '%')
                ->orWhere('title', 'LIKE', '%' . $request->product_name . '%');
            });
        }

        if ($category_id != 0) {
            $query->where('category_id', '=', $category_id);
        }

        // throw new Exception($vendor_id);
        if ($vendor_id > 0) {
            $query->where('product_items.vendor_id', '=', $vendor_id);
        }

        if ($quantity_greater > 0) {
            $query->where('current_stock', '>=', $quantity_greater);
        }

        if ($price_min) {
            $query->where('unit_price', '>=', $price_min);
        }

        if ($price_max) {
            $query->where('unit_price', '<=', $price_max);
        }

        if ($status != 'undefined') {
            if($status == 1){
                $status = 'publish';
            }else{
                $status = 'disabled';
            }
            $query->where('status', '=', $status);
        }

        if (!empty($product_dropdown1)) {
            $product_dropdown1_id = \App\Product\ProductDropDown1::where('name','=',$product_dropdown1)->value('id');
            $query->where('product_dropdown_1_id', '=', $product_dropdown1_id);
        }
        // throw new Exception($product_dropdown2);
        if (!empty($product_dropdown2)) {
            $product_dropdown2_id = \App\Product\ProductDropDown2::where('name','=',$product_dropdown2)->value('id');
            $query->where('product_dropdown_2_id', '=', $product_dropdown2_id);
        }

        // Return the query result
        return api([
            'data' => $query->search()
        ]);

        
    }
     

//     public function search(Request $request)
//     {
//         $user = auth()->user();

//         $request->validate([
//             'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
//         ]);

//         $categ1 = UserCategory::where('user_id','=',$user->id)->get();
//         $items = array(); 
//         foreach ($categ1 as $categ2)
//         $items[] = $categ2->category_id;
//         {
//         if(request()->has('vendor_id')) {
//             $results = DB::table('products')
//                 ->select(
//                     'products.id', 'products.minimum_stock','products.uom_id',
//                     DB::raw('concat(products.code, " - ", products.description) as text'),
//                     DB::raw('concat(uom.unit) as uom'),
//                     'product_items.price as vendor_price', 'product_items.vendor_id'
//                     ,'product_items.reference',
//                     // ,'product_items.tax_rate' ,'product_items.tax_name'
//                     'product_taxes.name','product_taxes.rate'
//                 )
//                 ->join('product_items', 'products.id', '=', 'product_items.product_id')
//                 ->join('product_taxes', 'products.id', '=', 'product_taxes.product_id')
//                 ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
//                 ->join('uom', 'uom.id', '=', 'products.uom_id')
//                 ->whereIn('category_id',$items)
//                 ->where('product_items.vendor_id', '=', request('vendor_id'))
//                 ->where(function($query) {
//                     $query->where('products.code', 'like', '%'.request('q').'%')
//                         ->orWhere('products.description', 'like', '%'.request('q').'%')
//                         ->orWhere('products.uom_id', 'like', '%'.request('q').'%')
//                         ->orWhere('product_items.reference', 'like', '%'.request('q').'%');
//                 })
//                 ->limit(6)
//                 ->get();
//         }
//         else if(request()->has('sub_category')) {
//             $results = Product::with('taxes','uom')
//             ->orderBy('code')
//             ->whereIn('category_id',$items)
//             ->where('products.sub_category_id', '=', request('sub_category_id'))
//             ->when(request('q'), function($query) {
//                 $query->where('code', 'like', '%'.request('q').'%')
//                     ->orWhere('description', 'like', '%'.request('q').'%');
//             })
//             ->limit(6)
//             ->get(['id', 'code', 'description', 'unit_price','uom_id']);
//         } 
//         else {
//             $results = Product::with('taxes','uom')
//                 ->orderBy('code')
//                 ->whereIn('category_id',$items)
//                 ->when(request('q'), function($query) {
//                     $query->where('code', 'like', '%'.request('q').'%')
//                         ->orWhere('description', 'like', '%'.request('q').'%');
//                 })
//                 ->limit(6)
//                 ->get(['id', 'code', 'description', 'minimum_stock','uom_id']);
//         }

//         return api([
//             'results' => $results
//         ]);
//     }
// }


public function showInvoices($id)
{
    $product = Product::findOrFail($id);

    // $model = $product->salesOrders()
    $model = DB::table('invoices')
    ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
    ->where('item_id','=',$id)
    ->orderby('date','desc')
    ->paginate(5);
    // ->get();
    // $model = \App\SalesOrder\SalesOrder::join('')
    //     ->orderBy('created_at', 'desc')
    //     ->paginate(5);

    return api([
        'model' => $model
    ]);
}

public function ContainerProducts(Request $request)
{
        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get(['id', 'code', 'description','tax_name','tax_rate', 'unit_price as price','uom','uom_id','warehouse_qty as qty_on_hand','ct_box','weight_box','volume_box']);

    return api([
        'results' => $results
    ]);


}

    public function vendor_products2(Request $request)
{
    $request->validate([
        'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
    ]);

    if(request()->has('vendor_id')) {
              $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->join('product_items', 'product_items.product_id', '=', 'products.id')
            ->leftjoin('product_taxes', 'products.id', '=', 'product_taxes.product_id')
            ->where('product_items.vendor_id', '=', request('vendor_id'))
            ->limit(100)
            ->where('status','=','publish')
            ->get(['product_taxes.name' ,'product_taxes.rate','product_items.price as vendor_price', 'product_items.vendor_id',
                'product_items.reference','products.id', 'code', 'description', 'unit_price as price','uom_id','unit','current_stock','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

        }else{
            throw new \Exception ("Select Vendor First");
        }
    return api([
        'results' => $results
    ]);
}

public function products_bill(Request $request)
{
    $request->validate([
        'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
    ]);

         if(request()->has('vendor_id')) {
              $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->join('product_items', 'product_items.product_id', '=', 'products.id')
            ->leftjoin('product_taxes', 'products.id', '=', 'product_taxes.product_id')
            ->where('product_items.vendor_id', '=', request('vendor_id'))
            ->limit(100)
            ->where('status','=','publish')
            ->get(['product_taxes.name' ,'product_taxes.rate','product_items.price as vendor_price', 'product_items.vendor_id',
                'product_items.reference','products.id', 'code', 'description', 'unit_price as price','uom_id','unit','current_stock','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

        }else{
            throw new \Exception ("Select Vendor First");
        }
    return api([
        'results' => $results
    ]);
}



public function SalesProducts(Request $request)
{
 

        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->limit(100)
            ->where('status','=','publish')
            ->get(['id', 'code', 'description', 'unit_price as price','uom_id','unit','current_stock','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

    return api([
        'results' => $results
    ]);
}


public function price_changes_products(Request $request)
{
 

        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->limit(100)
            ->where('status','=','publish')
            ->get(['id', 'code', 'description', 'unit_price as price','unit','current_stock','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

    return api([
        'results' => $results
    ]);
}



public function QuotationProducts(Request $request)
{
 

        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->limit(15)
            ->where('status','=','publish')
            ->get(['id', 'code','uom_id', 'description', 'unit_price','current_stock','unit','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price']);

    return api([
        'results' => $results
    ]);
}

public function InvoicesProducts(Request $request)
{
 

        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.$capitalize.'%')
                ->where('status','=','publish')
                    ->orWhere(DB::raw('LOWER(description)'), 'like', '%'.$capitalize.'%');
            })
            ->where('status','=','publish')
            ->orderBy('title','desc')
            ->limit(100)
            ->get(['id', 'code', 'uom_id', 'unit', 'description', 'unit_price as price','current_stock','unit','current_stock as qty_on_hand','location','class_a_price','class_b_price','class_c_price','sale_price as cost_price']);

    return api([
        'results' => $results
    ]);
}



public function search(Request $request)
{
    $request->validate([
        'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
    ]);

    if(request()->has('vendor_id')) {
        $results = DB::table('products')
            ->select(
                'products.id', 'products.unit_price','products.uom_id',
                DB::raw('concat(products.code, " - ", products.description) as text'),
                DB::raw('concat(uom.unit) as uom'),
                'product_items.price as vendor_price', 'product_items.vendor_id',
                'product_items.reference',
                'product_taxes.name','product_taxes.rate'
            )
            ->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->join('product_taxes', 'products.id', '=', 'product_taxes.product_id')
            ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
            ->join('uom', 'uom.id', '=', 'products.uom_id')
            ->where('product_items.vendor_id', '=', request('vendor_id'))
            ->where(function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where(DB::raw('LOWER(products.code)'), 'like', '%'. $capitalize .'%')
                    ->orWhere(DB::raw('LOWER(products.description)'), 'like', '%'. $capitalize .'%')
                    ->orWhere('products.uom_id', 'like', '%'. $capitalize .'%')
                    ->orWhere(DB::raw('LOWER(product_items.reference)'), 'like', '%'. $capitalize .'%')
                    ->where('products.status','=','publish');
            })
            ->where('products.status','=','publish')
            ->limit(15)
            ->get();
    } else {
        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where('code', 'like', '%'.request('q').'%')
                ->where('status','=','publish')
                ->orWhere(DB::raw('LOWER(products.description)'), 'like', '%'. $capitalize .'%');
            })
            ->where('status','=','publish')
            ->limit(15)
            ->get(['id', 'code', 'description', 'unit_price','uom','current_stock as qty_on_hand']);
    }

    return api([
        'results' => $results
    ]);
}

    public function products_aggregation(Request $request)
    {
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'code', 'description', 'unit_price','uom','current_stock']);

        return api([
            'results' => $results
        ]);
    }


    public function searcha(Request $request)
    {
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'code', 'description', 'unit_price','uom_id','qty_on_hand']);

        return api([
            'results' => $results
        ]);
    }

        

    public function search1(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $categ1 = UserCategory::where('user_id','=',$user->id)->get();
        $items = array(); 
        foreach ($categ1 as $categ2)
        $items[] = $categ2->category_id;
        {
                $results = Product::with('taxes','uom','items','vendor','vendor1')
                ->orderBy('code')
                ->whereIn('category_id',$items)
                // ->join('product_items', 'product_items.product_id', '=', 'products.id')
                // ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
                ->where('products.sub_category_id', '=', request('sub_category_id'))
                ->when(request('q'), function($query) {
                    $query->where('code', 'like', '%'.request('q').'%')
                        ->orWhere('description', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get();
         
         }
        return api([
            'results' => $results
        ]);
    }


    public function search2(Request $request)
    {
        if(request()->has('product_id')) {        
                $results = ProductItemSearch::with('vendor1')
                ->where('product_items.product_id', '=', request('product_id'))
                ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
                ->join('currencies', 'currencies.id', '=', 'product_items.currency_id')
                ->when(request('q'), function($query) {
                    $query->where('product_items.vendor_id', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get();
        }
                return api([
                    'results' => $results
                ]);
    }


    public function search3(Request $request)
    {
        if(request()->has('product_id')) {        
            $results = DB::table('products')
            ->select(
                DB::raw('concat(purchase_orders.number ," -  " , "Item :    ", products.description," -  " , "Item Price:    ", purchase_order_items.unit_price," ", currencies.code, " -  " ,"   PO Date : ", purchase_order_items.updated_at) as text')
                // ,
                // DB::raw('concat("PO ID#:" ,purchase_order_items.purchase_order_id, purchase_orders.number ,"-" , "PO Price#:", purchase_order_items.unit_price) as text'),
            )
            ->where('purchase_order_items.product_id', '=', $request->product_id)
            ->where('purchase_orders.status_id', '>', 2)
            ->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->join('purchase_order_items', 'purchase_order_items.product_id', '=', 'product_items.product_id')
            ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
            ->join('currencies', 'currencies.id', '=', 'purchase_orders.currency_id')
            // ->where(function($query) {
            //     $query->where('products.code', 'like', '%'.request('q').'%')
            //         ->orWhere('products.description', 'like', '%'.request('q').'%')
            //         ->orWhere('products.uom_id', 'like', '%'.request('q').'%')
            //         ->orWhere('product_items.reference', 'like', '%'.request('q').'%');
            // })
            ->distinct()
            ->limit(6)
            ->get();
        }
                return api([
                    'results' => $results
                ]);
    }


    public function search4(Request $request)
    {
        if(request()->has('vendor_id')) {        
            // $results =  DB::table('products')
            // ->select(
            //     'products.id', 'products.unit_price','products.uom_id',
            //     DB::raw('concat(products.code, " - ", products.description) as text'),
            //     DB::raw('concat(uom.unit) as uom'),
            //     DB::raw('concat(product_taxes.rate) as rate'),
            //     DB::raw('concat(product_taxes.name) as name'),
            //     'product_items.price as price', 'product_items.vendor_id','product_items.product_id',
            //     'product_items.reference','product_taxes.rate','product_taxes.name','product_taxes.product_id'
            //     )
            //     ->join('product_items', 'product_items.product_id', '=', 'products.id')
            //     ->join('product_taxes','product_items.product_id','=','product_taxes.product_id')
            //     ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
            //     ->join('uom', 'uom.id', '=', 'products.uom_id')
            //     ->where('product_items.vendor_id', '=', request('vendor_id'))
            //     ->limit(6)
            //     ->get();

            $results = Product::with('taxes','product','uom','vendor')
        //      ->join('product_items', 'product_items.product_id', '=', 'products.id')
        //      ->join('vendors', 'vendors.id', '=', 'product_items.vendor_id')
            // ->join('product_taxes','product_items.product_id','=','product_taxes.product_id')
            // ->join('uom', 'uom.id', '=', 'products.uom_id')
            // ->where('product_items.vendor_id', '=', request('vendor_id'))
        //     ->where(function($query) {
        //         $query->where('products.code', 'like', '%'.request('q').'%')
        //             ->orWhere('products.description', 'like', '%'.request('q').'%')
        //             ->orWhere('products.uom_id', 'like', '%'.request('q').'%');
        //             ->orWhere('product_items.reference', 'like', '%'.request('q').'%');
        //     })
            ->limit(6)
            ->get();
        }
                return api([
                    'results' => $results
                ]);
    }

    public function search5 (Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);

        $categ1 = UserCategory::where('user_id','=',$user->id)->get();
        $items = array(); 
        foreach ($categ1 as $categ2)
        $items[] = $categ2->category_id;
        {
        if(request()->has('sub_category_id')) {
            $results = Product::with('taxes','uom')
            ->orderBy('code')
            ->whereIn('category_id',$items)
            ->where('products.sub_category_id', '=', request('sub_category_id'))
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'code', 'description','qty_on_hand' ,'minimum_stock','uom_id']);
        } 
    

        return api([
            'results' => $results
        ]);
        }
    }

    
    public function create()
    {
        $user = auth()->user();
        if ($user->is_products_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $form = array_merge([
                'code' => counter()->next('product'),
                'minimum_stock' => 0,
                'description' => '',
                'has_inventory' => 0,
                'product_type' => 2,
                'unit_price' => 0,
                'qty_on_hand' => 0,
                'class_a_price' => 0,
                'class_b_price' => 0,
                'class_c_price' => 0,
                'nb_boxes_1' => 0,
                'nb_boxes_2' => 0,
                'nb_boxes_3' => 0,
                'nb_boxes_1_price' => 0,
                'nb_boxes_2_price' => 0,
                'nb_boxes_3_price' => 0,
                'item_box' => 1,
                // 'uom_id' => 0,
                // 'category_id' => 0,
                // 'sub_category_id' => 0,
                // 'sub_sub_category_id' => 0,
                // 'warehouse_id' => 1,
                'items' => [],
                'attributes' => [],
                'values' => [],
                'attributes_value' => [],
                'taxes' => [],
                'conversions' => [],
            ],
                currency()->defaultToArray()
            );

            return api([
                'form' => $form
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
                $request->validate([
                    'description' => 'required',
                    'minimum_stock' => 'required|numeric|min:0',
                    'currency_id' => 'required|integer|exists:currencies,id',
                    'has_inventory' => 'nullable',
                    'unit_price' => 'required',
                    'sale_price' => 'required',
                    'product_type' => 'nullable',
                    'uom_id' => 'required|min:0',
                    'raw_material_type_id' => 'nullable|min:0',
                    'category_id' => 'required|min:0',
                    'sub_category_id' => 'required|min:0',
                    // 'sub_sub_category_id' => 'nullable',
                    'warehouse_id' => 'required|min:0',
                    'items' => 'sometimes|array',
                    'items.*.reference' => 'nullable|max:255',
                    'items.*.vendor_id' => 'nullable|integer|exists:vendors,id',
                    'items.*.price' => 'nullable|numeric|min:0',
                    'items.*.currency_id' => 'nullable|integer|exists:currencies,id',
                    'items.*.tax_name' => 'nullable|max:255',
                    'items.*.tax_rate' => 'nullable|numeric|min:0',
                    'class_a_price' => 'required|max:255',
                    'class_b_price' => 'required|max:255',
                    'class_c_price' => 'required|max:255',
                    'values' => 'sometimes|array',
                    'values.*.attribute_id'=> 'nullable',
                    'values.*.attribute_value'=> 'nullable',
                    'taxes' => 'nullable|array',//
                    'taxes.*.name' => 'nullable|max:255',
                    'taxes.*.rate' => 'nullable|numeric|min:0',
                    'taxes.*.tax_authority' => 'nullable|max:255'
                ]);
        
                $model = new Product;
                $model->fill($request->except('items', 'taxes','values','conversions'));
                $model->id = $request->code;
                $model->user_id = auth()->id();
              
                $username = Auth::user()->name;
                $model->status = 'Manually Creation';
                $model ->created_by = $username;
                $model ->thumbnail  = 'placeholder.png';
                // $model ->company = '1';
                $model ->category_id  = $request->category_id;
                $model->sub_category_id = '["'.$request->sub_category_id.'"]';
                $model->sub_categoryid = $request->sub_category_id;
                // $model->sub_sub_categoryid = 0;
                // $model->sub_sub_category_id = '[""]';

                $model->sub_sub_categoryid = $request->sub_sub_categoryid;
                $model->sub_sub_category_id = '["'.$request->sub_sub_categoryid.'"]';
        
       
            $model ->product_image_gallery  = '["9999999"]';
             $model ->image  = '9999999';
           $model ->thumbnail  = 'placeholder.png';
        $model ->images  = '["placeholder.png"]';
       
       
                $model ->category_ids  = '[{"id":"'.$request->category_id.'","position":1}]';
                $model->title = $request->description;
                $model->summary = $request->description;
                $model->name = $request->description;
                $model->tag = $request->description;
                $model->details = $request->description;
                $model->unit = $request->uom_unit;
                $model->tax = 0;
                $model->tax_type = 'percent';
                $model->tax_model = 'exclude';
                $model->shipping_cost = 0;
                $model->multiply_qty = 0;
                $model->unit_price = $request->unit_price;
                $model->original_price = $request->unit_price;
                $model->purchase_price = $request->unit_price;
                $model->price = $request->unit_price;
                $model->sale_price = $request->sale_price;
                $model ->thumbnail  = 'placeholder.png';
                $model ->images  = '["placeholder.png"]';
                $model ->status  = $request->status;
                $model ->product_type  = 'physical';
                $model ->added_by  = 'seller';
                $model ->slug  = counter()->next('product');
                $model->tax_percentage = 0;
                $model ->attributes  = '[]';
                // $model->brand_id = 0;
                $model->discount = 0;
                $model->discount_type = 'flat';
                $model->minimum_order_qty = 0;
                $model->free_shipping = 0;
                $model ->colors  = '[]';
                $model->featured_status = 1;
                $model->request_status = 1;
                $model->published = 1;
                $model ->variation  = '[]';
                $model ->choice_options  = '[]';
                $model->refundable = 1;
                $model->min_qty = 1;
                $model->meta_image = 'def.png';
                $model ->color_image  = '[]';

    if($request->uom_id){
       $model ->uom_id = $request->uom_id;
    }else{
        $model->unit = 'PC';
        $model->uom_id = 1;
    }
                //sale_price is cost_price
                if($request->sale_price > 0){
                    $rating_value = ((($request->sale_price - $request->unit_price) / $request->sale_price) * 100);
                    $model->rating_value = (($request->sale_price - $request->unit_price) / $request->sale_price) * 100;
                    if($rating_value <= 5){
                        $model->product_rating = 1;
                    }
                    else if($rating_value > 5 && $rating_value <= 10){
                        $model->product_rating = 2;
                    }
                    else if($rating_value > 10 && $rating_value <= 15){
                        $model->product_rating = 3;
                    }
                    else if($rating_value > 10 && $rating_value <= 15){
                        $model->product_rating = 3;
                    }
                    else if($rating_value > 15 && $rating_value <= 20){
                        $model->product_rating = 4;
                    }
                    else{
                        $model->product_rating = 5;
                    }
                }
                

                if($request->nb_boxes_1 < 0){
                    $model->nb_boxes_1 = 1;
                    $model->nb_boxes_1_price = $request->unit_price;
                }
        
                if($request->nb_boxes_2 < 0){
                    $model->nb_boxes_2 = 1;
                    $model->nb_boxes_2_price = $request->unit_price;
                }
        
                if($request->nb_boxes_3 < 0){
                    $model->nb_boxes_3 = 1;
                    $model->nb_boxes_3_price = $request->unit_price;
                }
                
                
                $subcategoryCode = DB::table('sub_category')
                ->latest()->take(1)
                ->where('id','=',$request->sub_category_id)
                ->value('number');

                // $barcode = new BarcodeGenerator();
                // $barcode->setText($subcategoryCode.counter()->next('product'));
                // $barcode->setType(BarcodeGenerator::Code39);
                // $barcode->setScale(1,5);
                // $barcode->setThickness(25);
                // $barcode->setFontSize(10);
                // $code = $barcode->generate();

                // $code = \DNS1D::getBarcodeHTML($subcategoryCode.counter()->next('product'), 'C39',3,33,array(1,1,1));

                // $model->barcode = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
                // $barcode = \DNS1D::getBarcodeHTML(counter()->next('product'), 'C39',1.2,33);
                // $code = \DNS1D::getBarcodePNG($subcategoryCode.counter()->next('product'), 'C39',1.05,40,array(1,1,1,1), true);
                // $code = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
                $barcode = \DNS1D::getBarcodePNG(counter()->next('product'), 'C39',1,30);
                $model->barcode = $barcode;

                $result = DB::transaction(function() use ($model, $request) {
                    $lastCategNumb = Category::where('id','=',$request->parent_id)->take(1)->latest()->value('number');
                    $lastSubCategNumb = SubCategory::where('id','=',$request->sub_category_id)->take(1)->latest()->value('number');
                    // $lastSubSubCategNumb = SubSubCategory::where('id','=',$request->sub_sub_category_id)->take(1)->latest()->value('number');

                    // $model->code = counter()->next('product');
                    $model->id = $request->code;
                    $model->storeHasMany([
                        'items' => $request->items,
                        'taxes' => $request->taxes,
                        'conversions' => $request->conversions,
                    ]);
        
                    counter()->increment('product');
        
                    return $model;
                });

                $inventory = new Inventory;
                $inventory->product_id = $model->id;
                $inventory->sku = $model->id;
                $inventory->stock_count = $request->current_stock;
                $inventory->sold_count = 0;
                $inventory->save();
                // $vendor_id = ProductItem::where('product_id','=',$model->id)->orderby('created_at','desc')->value('vendor_id');
                // Product::where('id','=', $model->id)->update(['vendor_id' => $vendor_id]);
                
                $get_price = Product::where('id','=',$model->id)->value('unit_price');
                $get_uom = Product::where('id','=',$model->id)->value('unit');
                $get_currency = Product::where('id','=',$model->id)->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$model->id)->value('vendor_id');
                $get_warehouse = Product::where('id','=',$model->id)->value('warehouse_id');
                $get_category = Product::where('id','=',$model->id)->value('category_id');
                $get_sub_category = Product::where('id','=',$model->id)->value('sub_category_id');
                $get_name = Product::where('id','=',$model->id)->value('description');
                $get_code = Product::where('id','=',$model->id)->value('code');
                
                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $model->id;
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->warehouse_id = $get_warehouse;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  '0';
                $stock_movement->uom = $get_uom;
                $stock_movement->price = $get_price ;
                $stock_movement->currency = $get_currency;
                $stock_movement->type = "Initial Stock";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();
        
                //sale_price is cost
                $price_changes = new PriceChanges;
                $price_changes->product_id = $model->id;
                $price_changes->unit_price = $request->unit_price;
                $price_changes->unitprice = $request->unit_price / $request->item_box;
                $price_changes->class_a_price = $request->class_a_price;
                $price_changes->class_b_price = $request->class_b_price;
                $price_changes->class_c_price = $request->class_c_price;
                $price_changes->nb_boxes_1 = $request->nb_boxes_1;
                $price_changes->nb_boxes_1_price = $request->nb_boxes_1_price;
                $price_changes->nb_boxes_2 = $request->nb_boxes_2;
                $price_changes->nb_boxes_2_price = $request->nb_boxes_2_price;
                $price_changes->nb_boxes_3 = $request->nb_boxes_3;
                $price_changes->nb_boxes_3_price = $request->nb_boxes_3_price;
                $price_changes->sale_price = $request->sale_price;
                $price_changes->comment = 'New Stock';
                $price_changes->date = date('Y-m-d');
                $price_changes->time = now();
                $price_changes->save();

                DB::table('product_inventories')
                ->where('product_id', $result->id)
                ->update(['stock_count' => $request->current_stock]);
        
               $product_log = new \App\Product\ProductLog();
                $product_log->body = \App\Product\Product::where('id','=',$model->id)->get();
                $product_log->comment = "Store"  ;
                $product_log->items = \App\Product\Item::where('product_id','=',$model->id)->get();
                $product_log->save();
                
        
                return api([
                    'saved' => true,
                    'id' => $result->id
                ]);
            }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
       
            $data = Product::with([
                'currency', 'items.currency', 'items.vendor', 'taxes','uom','warehouse','category','sub_category'
                ])->findOrFail($id);

            return api([
                'data' => $data
            ]);
        }
    }

    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
                    $form = Product::with(['brand','product_dropdown_1','product_dropdown_2','currency', 'items.currency',
                     'items.vendor','conversions','conversions.converted_uom', 'taxes','uom','warehouse','category','sub_category','sub_sub_category'])
                    ->findOrFail($id);

            return api([
                'form' => $form
            ]);
        }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
          $user = auth()->user();
        $customPaper = array(0,0,150,291);
        $products = Product::with(['currency', 'items.currency','uom','warehouse','category','sub_category'])->findOrFail($id);
        $model = $products;
        $doc  = 'docs.barcode_label_print';
        //    return View('docs.barcode_label_print', compact('model'));
       return wasteLabel($doc, $products);
        $model = $products;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.barcode_label', compact('products'));
        }
    }


        public function label_barcode ($id)
    {
        // dd($id);
        $user = auth()->user();
        $customPaper = array(0,0,150,291);
        $products = Product::with(['currency', 'items.currency','uom','warehouse','category','sub_category'])->findOrFail($id);
        $model = $products;
        $doc  = 'docs.barcode_label_print';
        //    return View('docs.barcode_label_print', compact('model'));
       return wasteLabel($doc, $products);
        $model = $products;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.barcode_label', compact('products'));
      //   return View('docs.barcode_label_print', compact('model'));
    }

            public function receive_label_barcode ($id)
    {
        // dd($id);
        $user = auth()->user();
        $customPaper = array(0,0,150,291);
        $products = \App\Product\Lots::with(['uom','warehouse','category','sub_category','vendor'])->where('receive_order_id','=',$id)->where('qty','>',0)->get();
        $model = $products;
        $doc  = 'docs.barcode_receive_label_print';
        //    return View('docs.barcode_label_print', compact('model'));
       return receiveLotsLabel($doc, $products);
        $model = $products;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.barcode_receive_label_print', compact('products'));
      //   return View('docs.barcode_label_print', compact('model'));
    }
    
              public function receive_label_barcode_id ($id)
    {
        // dd($id);
        $user = auth()->user();
        $customPaper = array(0,0,150,291);
        $products = \App\Product\Lots::with(['uom','warehouse','category','sub_category','vendor'])->where('product_id','=',$id)->where('qty','>',0)->get();
        $model = $products;
        $doc  = 'docs.barcode_receive_label_print';
        //    return View('docs.barcode_label_print', compact('model'));
       return receiveLotsLabel($doc, $products);
        $model = $products;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.barcode_receive_label_print', compact('products'));
      //   return View('docs.barcode_label_print', compact('model'));
    }
    
               public function receive_label_barcode_lot_id ($id)
    {
        // dd($id);
        $user = auth()->user();
        $customPaper = array(0,0,150,291);
        $products = \App\Product\Lots::with(['uom','warehouse','category','sub_category','vendor'])->where('id','=',$id)->where('qty','>',0)->get();
        $model = $products;
        $doc  = 'docs.barcode_receive_label_print';
        //    return View('docs.barcode_label_print', compact('model'));
       return receiveLotsLabel($doc, $products);
        $model = $products;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.barcode_receive_label_print', compact('products'));
      //   return View('docs.barcode_label_print', compact('model'));
    }

    
    
    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_products_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Product::findOrFail($id);

        $changeunit_price = $model->unit_price;
        $changeunitprice = $model->unitprice;
        $changeclass_a_price = $model->class_a_price;
        $changeclass_b_price = $model->class_b_price;
        $changeclass_c_price = $model->class_c_price;
        $changenb_boxes_1 = $model->nb_boxes_1;
        $changenb_boxes_1_price = $model->nb_boxes_1_price;
        $changenb_boxes_2 = $model->nb_boxes_2;
        $changenb_boxes_2_price = $model->nb_boxes_2_price;
        $changenb_boxes_3 = $model->nb_boxes_3;
        $changenb_boxes_3_price = $model->nb_boxes_3_price;

        $changenb_sale_price = $model->sale_price;
        DB::table('test')
                ->where('id', 2)
                ->update(['body' => $changenb_boxes_3_price ]);

        if(
            (($changeunit_price) != ($request->unit_price)) || 
            (($changeunitprice) != ($request->unitprice)) ||
            (($changeclass_a_price) != ($request->class_a_price)) ||
            (($changeclass_b_price) != ($request->class_b_price)) ||
            (($changeclass_c_price) != ($request->class_c_price)) ||
            (($changenb_boxes_1) != ($request->nb_boxes_1)) ||
            (($changenb_boxes_1_price) != ($request->nb_boxes_1_price)) ||
            (($changenb_boxes_2) != ($request->nb_boxes_2)) ||
            (($changenb_boxes_2_price) != ($request->nb_boxes_2_price)) ||
            (($changenb_boxes_3) != ($request->nb_boxes_3)) ||
            (($changenb_boxes_3_price) != ($request->nb_boxes_3_price)) ||
            (($changenb_sale_price) != ($request->sale_price))

        ){
            $price_changes = new PriceChanges;
            $price_changes->product_id = $id;
            $price_changes->product_name = $request->description;
            $price_changes->unit_price = $request->unit_price;
            // $price_changes->unitprice = $request->unitprice;
            $price_changes->unitprice = $request->unit_price / $request->item_box;
            $price_changes->class_a_price = $request->class_a_price;
            $price_changes->class_b_price = $request->class_b_price;
            $price_changes->class_c_price = $request->class_c_price;
            $price_changes->nb_boxes_1 = $request->nb_boxes_1;
            $price_changes->nb_boxes_1_price = $request->nb_boxes_1_price;
            $price_changes->nb_boxes_2 = $request->nb_boxes_2;
            $price_changes->nb_boxes_2_price = $request->nb_boxes_2_price;
            $price_changes->nb_boxes_3 = $request->nb_boxes_3;
            $price_changes->nb_boxes_3_price = $request->nb_boxes_3_price;
            $price_changes->sale_price = $request->sale_price;
            $price_changes->comment = 'Edited Stock';
            $price_changes->date = date('Y-m-d');
            $price_changes->time = now();
            $price_changes->save();
        }
        
        $request->validate([
            'description' => 'nullable',
            'minimum_stock' => 'nullable',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'has_inventory' => 'nullable',
            'uom_id' => 'nullable',
            'product_type' => 'nullable',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'warehouse_id' => 'required',
            'sale_price' => 'required',
            'unit_price' => 'required',
            'items' => 'sometimes|array',
            'items.*.reference' => 'nullable',
            'items.*.vendor_id' => 'nullable|integer|exists:vendors,id',
            'items.*.price' => 'nullable',
            'items.*.currency_id' => 'nullable|integer|exists:currencies,id',
            'class_a_price' => 'required|max:255',
            'class_b_price' => 'required|max:255',
            'class_c_price' => 'required|max:255',
            'values' => 'sometimes|array',
            'values.*.attribute_id'=> 'nullable',
            'values.*.attribute_value'=> 'nullable',
            'taxes' => 'nullable|array',
            'taxes.*.name' => 'nullable',
            'taxes.*.rate' => 'nullable',
            'taxes.*.tax_authority' => 'nullable'
        ]);

        $qty = $model->current_stock;
        $model->fill($request->except('items', 'taxes','values','conversions'));
        $username = Auth::user()->name;
        // $model->status = 'Manually Update';
        $model ->updated_by = $username;
        $model ->category_id  = $request->category_id;
        
        $model->id = $request->code;
         $model ->body  = preg_replace('/[^0-9]/', '', $request->sub_category_id);
         $request_sub_category_id = preg_replace('/[^0-9]/', '', $request->sub_category_id);
        $sub_categoryid = Product::where('id','=',$model->id)->value('sub_categoryid');
        
        if($request_sub_category_id){
         
        }else{
            $request_sub_category_id = 0;
            
        }
           $model->sub_category_id = '["'.$request_sub_category_id.'"]';
            $model->sub_categoryid = $request_sub_category_id;
       
        $model->sub_sub_categoryid = $request->sub_sub_categoryid;
        $model->sub_sub_category_id = '["'.$request->sub_sub_categoryid.'"]';

        $model ->category_ids  = '[{"id":"'.$request->category_id.'","position":1}]';
        
   
        
        $model->title = $request->description;
        $model->summary = $request->description;
        $model->name = $request->description;
        $model->tag = $request->description;
        $model->details = $request->description;

        // throw new \Exception ($request->uom_id);
        $model->unit = \App\Uom::where('id','=',$request->uom_id)->value('unit');
        $model->tax = 0;
        $model->tax_type = 'percent';
        $model->tax_model = 'exclude';
        $model->shipping_cost = 0;
        $model->multiply_qty = 0;
        $model->unit_price = $request->unit_price;
        $model->unitprice = $request->unit_price / $request->item_box;
        $model->original_price = $request->unit_price;
        
        $model->purchase_price = $request->unit_price;
        $model->price = $request->unit_price;
        $model->sale_price = $request->sale_price;
        // $model ->thumbnail  = 'placeholder.png';
        // $model ->images  = '["placeholder.png"]';
         if($model->thumbnail != null){

        }else{
            $model ->thumbnail  = 'placeholder.png';
        }
        if($model->images != null){

        }else{
            $model ->images  = '["placeholder.png"]';
        }
        
        if($model->product_image_gallery != null){

        }else{
            $model ->product_image_gallery  = '["9999999"]';
        }
        
         if($model->image != null){

        }else{
            $model ->image  = '9999999';
        }
       
        
        $model ->status  = $request->status;
        $model ->product_type  = 'physical';
        $model ->added_by  = 'seller';
        $model ->slug  = $id;
        $model->tax_percentage = 0;
        $model ->attributes  = '[]';
        // $model->brand_id = 0;
        $model->discount = 0;
        $model->discount_type = 'flat';
        $model->minimum_order_qty = 0;
        $model->free_shipping = 0;
        $model ->colors  = '[]';
        $model->featured_status = 1;
        $model->request_status = 1;
        $model->published = 1;
        $model ->variation  = '[]';
        $model ->choice_options  = '[]';
        $model->refundable = 1;
        $model->min_qty = 1;
        $model->meta_image = 'def.png';
        $model ->color_image  = '[]';

        $class_a_price = $model->class_a_price;
        $class_b_price = $model->class_b_price;
        $class_c_price = $model->class_c_price;
        $old_rating_value = $model->rating_value;
        if($old_rating_value == 0){
            $old_rating_value = 1;
        }
        $rating_value = $request->rating_value;
        $get_rating_value = Product::where('id','=',$id)->value('rating_value');
        $get_sale_price = Product::where('id','=',$id)->value('unit_price');

       
        if($get_sale_price == $request->unit_price){
            $rating_value = $request->rating_value;
            if($get_rating_value != $request->rating_value){
                if($request->rating_value > 0){
                    $rating_value = $request->rating_value;
                    $model->rating_value = $request->rating_value;
                    $model->unit_price = $request->sale_price + (($request->sale_price / 100 ) * $request->rating_value);
                    
                  if($request->adjsut_classes == 1){
                    $model->class_a_price = (($request->class_a_price * $request->rating_value) / $old_rating_value);
                    $model->class_b_price = ($request->class_b_price * $request->rating_value) / $old_rating_value;
                    $model->class_c_price = ($request->class_c_price * $request->rating_value) / $old_rating_value;
                  }
                    
                }
            }
        }else if($get_rating_value != $request->rating_value){
            if($request->rating_value > 0){
                $rating_value = $request->rating_value;
                $model->rating_value = $request->rating_value;
                $model->unit_price = ($request->sale_price * $request->rating_value) + $request->sale_price;
                
              if($request->adjsut_classes == 1){
                $model->class_a_price = ($request->class_a_price * $request->rating_value) / $old_rating_value;
                $model->class_b_price = ($request->class_b_price * $request->rating_value) / $old_rating_value;
                $model->class_c_price = ($request->class_c_price * $request->rating_value) / $old_rating_value;
              }
                
            }
        }else{
            $rating_value = ((($request->unit_price - $request->sale_price) / $request->unit_price) * 100);
            
            if($request->adjsut_classes == 1){
                $model->class_a_price = ($request->class_a_price * $request->rating_value) / $old_rating_value;
                $model->class_b_price = ($request->class_b_price * $request->rating_value) / $old_rating_value;
                $model->class_c_price = ($request->class_c_price * $request->rating_value) / $old_rating_value;
            }
            
            
            $model->rating_value = (($request->unit_price - $request->sale_price) / $request->unit_price) * 100;
        }

        //updates 27-04-2024
        if($rating_value < 1)
        {
            $model->rating_value = (($request->unit_price - $request->sale_price) / $request->unit_price) * 100;
        }else{
            $model->rating_value = (($request->unit_price - $request->sale_price) / $request->unit_price) * 100;
        }

        if($rating_value <= 5){
            $model->product_rating = 1;
        }
        else if($rating_value > 5 && $rating_value <= 10){
            $model->product_rating = 2;
        }
        else if($rating_value > 10 && $rating_value <= 15){
            $model->product_rating = 3;
        }
        else if($rating_value > 10 && $rating_value <= 15){
            $model->product_rating = 3;
        }
        else if($rating_value > 15 && $rating_value <= 20){
            $model->product_rating = 4;
        }
        else{
            $model->product_rating = 5;
        }


        if($request->nb_boxes_1 < 0){
            $model->nb_boxes_1 = 1;
            $model->nb_boxes_1_price = $request->unit_price;
        }

        if($request->nb_boxes_2 < 0){
            $model->nb_boxes_2 = 1;
            $model->nb_boxes_2_price = $request->unit_price;
        }

        if($request->nb_boxes_3 < 0){
            $model->nb_boxes_3 = 1;
            $model->nb_boxes_3_price = $request->unit_price;
        }
     

        // $model ->uom = $request->unit;
        // $model ->unit = $request->uom;

        // $code =  Product::where('id','=',$id)->value('code');
        // $barcode = new BarcodeGenerator();
        // $barcode->setText($code);
        // $barcode->setType(BarcodeGenerator::Code39);
        // $barcode->setScale(1,5);
        // $barcode->setThickness(25);
        // $barcode->setFontSize(10);
        // $code = $barcode->generate();
        //$code = \DNS1D::getBarcodeHTML($code, 'C39',1.05,25);
        // $code = \DNS1D::getBarcodePNG($code, 'C39',1.05,40,array(1,1,1,1), true);
        // $code = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
        // $model->barcode = $code;

        $barcode = \DNS1D::getBarcodePNG($model ->id, 'C39',33);
        $model->barcode = $barcode;
                
        // only update if no qty in hand
        if($qty == 0) {
            if($request->has('current_stock')) {
                $model->current_stock = $request->current_stock;
            }
        }

        DB::table('product_inventories')
        ->where('product_id', $id)
        ->update(['stock_count' => $request->current_stock]);

        $result = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items' => $request->items,
                'taxes' => $request->taxes,
                'conversions' => $request->conversions,
            ]);

            return $model;
        });

        $inventory = Inventory::where('product_id','=',$id)->value('id');
        if($inventory){
            $inventory = Inventory::where('product_id','=',$id)->update(['stock_count' => $request->current_stock]);
        }else{
            $inventory = new Inventory;
            $inventory->product_id = $model->id;
            $inventory->sku = $model->id;
            $inventory->stock_count = $request->current_stock;
            $inventory->sold_count = 0;
            $inventory->save();
        }
        

                
                
        $vendor_id = ProductItem::where('product_id','=',$id)->value('vendor_id');
        if($vendor_id){
            Product::where('id','=', $id)->update(['vendor_id' => $vendor_id]);
        }
        

        $get_price = Product::where('id','=',$model->id)->value('unit_price');
        $get_uom = Product::where('id','=',$model->id)->value('unit');
        $get_currency = Product::where('id','=',$model->id)->value('currency_id');
        $get_vendor = ProductItem::where('product_id','=',$model->id)->value('vendor_id');
        $get_warehouse = Product::where('id','=',$model->id)->value('warehouse_id');
        $get_category = Product::where('id','=',$model->id)->value('category_id');
        $get_sub_category = Product::where('id','=',$model->id)->value('sub_category_id');
        $get_name = Product::where('id','=',$model->id)->value('description');
        $get_code = Product::where('id','=',$model->id)->value('code');
        
        $stock_movement = new StockMovement();
        $stock_movement->user_id = auth()->id();
        $stock_movement->product_id = $model->id;
        $stock_movement->product_code = $get_code;
        $stock_movement->product_name = $get_name;
        $stock_movement->warehouse_id = $get_warehouse;
        $stock_movement->category_id = $get_category;
        $stock_movement->sub_category_id = $get_sub_category;
        $stock_movement->vendor_id = $get_vendor;
        $stock_movement->qty =  '0';
        $stock_movement->uom = $get_uom;
        $stock_movement->price = $get_price ;
        $stock_movement->currency = $get_currency;
        $stock_movement->type = "Edited Stock";
        $stock_movement->created_by = Auth::user()->name;
        $stock_movement->save();


                 
                    $product_log = new \App\Product\ProductLog();
        $product_log->body = \App\Product\Product::where('id','=',$model->id)->get();
        $product_log->comment = "Update"  ;
        $product_log->items = \App\Product\Item::where('product_id','=',$id)->get();
        $product_log->save();
        

        return api([
            'saved' => true,
            'id' => $result->id
        ]);
    }
    }


    public function markAs1($id, Request $request)
    {
        $model = Product::findOrFail($id);
        $model->status = 'publish';
        $model->save();

       return back();
    }

    public function markAs2($id, Request $request)
    {
        $model = Product::findOrFail($id);
        $model->status = 'disabled';
        $model->save();
        return back();
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_products_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Product::findOrFail($id);

            // check whether this particular product belongs to
            $items = $model->items()->count();

            $lots = $model->lots()->count();

            // throw new \Exception ($lots);
            // quotation
            // $quotations = DB::table('quotation_items')
                // ->where('product_id', $model->id)->count();

            // sales order
            // $salesOrders = DB::table('sales_order_items')
                // ->where('product_id', $model->id)->count();
            // invoice

            // $invoices = DB::table('invoice_items')
                // ->where('product_id', $model->id)->count();
            // purchase order

            $purchaseOrders = DB::table('purchase_order_items')
                ->where('product_id', $model->id)->count();
            // bills

            // $bills = DB::table('bill_items')
                // ->where('product_id', $model->id)->count();
            // if yes provide warning

            if($items || $purchaseOrders ||  $lots) {
                return api([
                    'message' => 'Delete all the product relations first',
                    'errors' => []
                ], 422);
            }

        $get_price = Product::where('id','=',$model->id)->value('unit_price');
        $get_uom = Product::where('id','=',$model->id)->value('uom_id');
        $get_currency = Product::where('id','=',$model->id)->value('currency_id');
        $get_vendor = ProductItem::where('product_id','=',$model->id)->value('vendor_id');
        $get_warehouse = Product::where('id','=',$model->id)->value('warehouse_id');
        $get_category = Product::where('id','=',$model->id)->value('category_id');
        $get_sub_category = Product::where('id','=',$model->id)->value('sub_category_id');
        $get_name = Product::where('id','=',$model->id)->value('description');
        $get_code = Product::where('id','=',$model->id)->value('code');
        
        $stock_movement = new StockMovement();
        $stock_movement->user_id = auth()->id();
        $stock_movement->product_id = $model->id;
        $stock_movement->product_code = $get_code;
        $stock_movement->product_name = $get_name;
        $stock_movement->warehouse_id = $get_warehouse;
        $stock_movement->category_id = $get_category;
        $stock_movement->sub_category_id = $get_sub_category;
        $stock_movement->vendor_id = $get_vendor;
        $stock_movement->qty =  '0';
        $stock_movement->uom = $get_uom;
        $stock_movement->price = $get_price ;
        $stock_movement->currency = $get_currency;
        $stock_movement->type = "Deleted Stock";
        $stock_movement->created_by = Auth::user()->name;
        $stock_movement->save();

        $product_log = new \App\Product\ProductLog();
        $product_log->body = \App\Product\Product::where('id','=',$id)->get();
        $product_log->items = $model->items();
        $product_log->comment = "Deleted"  ;
        $product_log->save();
                
            $model->items()->delete();
            $model->delete();

            
            return api([
                'deleted' => true
            ]);
        }
    }
}
