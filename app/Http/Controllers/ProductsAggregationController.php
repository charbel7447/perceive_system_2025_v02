<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductsAggregation\ProductsAggregation;
use App\ProductsAggregation\Item;

use App\Product\Product;
use App\Product\Item as ProductItem;


use DB;
use Auth;
use App\UserCategory;
use App\Category;
use App\SubCategory;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use PDF;
use App\StockMovement\StockMovement;

class ProductsAggregationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_productsaggregation_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ProductsAggregation::with(['currency','category','sub_category','items.product','items.uom','uom'])->search()
            ]);
        }
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $code = Product::latest()->orderBy('id','desc')->value('id');
        if ($user->is_productsaggregation_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'code' => counter()->next('product'),
            'minimum_stock' => 0,
            'description' => '',
            'has_inventory' => 0,
            'uom_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'warehouse_id' => 1,
            'items' => [],
        ],
            currency()->defaultToArray()
        );

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
        if ($user->is_productsaggregation_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
                $request->validate([
                    'description' => 'required',
                    'minimum_stock' => 'nullable|numeric|min:0',
                    'currency_id' => 'required|integer|exists:currencies,id',
                    'has_inventory' => 'nullable',
                    'uom_id' => 'required|min:0',
                    'category_id' => 'required|min:0',
                    'sub_category_id' => 'required|min:0',
                    'warehouse_id' => 'required|min:0',
                    'items' => 'sometimes|array',
                    'items.*.product_aggregation_id' => 'nullable|max:255',
                    'items.*.product_id' => 'nullable|max:255',
                    'items.*.product_code' => 'nullable',
                    'items.*.product_name' => 'nullable',
                    'items.*.product_price' => 'nullable',
                    'items.*.uom_id' => 'nullable',
                    'items.*.current_stock' => 'nullable|numeric|min:0',
                ]);
        
                $model = new ProductsAggregation;
                $model->fill($request->except('items'));
        
                $model->user_id = auth()->id();
                $model->has_inventory = $request->has_inventory;
                $username = Auth::user()->name;
                $model->status = 'publish';
                $model ->created_by = $username;
                $model->code = $request->code;
                $model->item_code = $request->code;
                $model->sub_sub_category_id = $request->sub_sub_category_id;
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

                $new_code = counter()->next('product');

                $result = DB::transaction(function() use ($model, $request) {
                    $lastCategNumb = Category::where('id','=',$request->parent_id)->take(1)->latest()->value('number');
                    $lastSubCategNumb = SubCategory::where('id','=',$request->sub_category_id)->take(1)->latest()->value('number');
                    // $lastSubSubCategNumb = SubSubCategory::where('id','=',$request->sub_sub_category_id)->take(1)->latest()->value('number');

                    
                    $model->storeHasMany([
                        'items' => $request->items,
                    ]);
                
                    $items1 = collect($request->items);
                    $product_id_a = 0;

                    foreach($items1 as $item){
                        $product_id_a = $item['product_id'];
                    }

                    $product = new Product;
                    $product->code = counter()->next('product');
                    $product->user_id = auth()->id();
                    $product->description = $request->description;
                    $product->uom = $request->uom_id;
                    $product->current_stock = $request->current_stock;
                    $product->warehouse_id = $request->warehouse_id;
                    $product->category_id = $request->category_id;
                    $product->sub_category_id = $request->sub_category_id;
                    $product->sub_sub_category_id = $request->sub_sub_category_id;
                    $product->currency_id = $request->currency_id;
                    $product->status = 'Aggregation Product';
                     //api
                    $product->title = $request->description; 
                    $product->summary = $request->description;
                    $product->name = $request->description;
                    $product->tag = $request->description;
                    $product->details = $request->description;
                    $product->sub_categoryid =  $request->sub_category_id;
                    $product->sub_sub_categoryid = $request->sub_sub_category_id;
                    $product->sub_sub_category_id = $request->sub_sub_category_id;
                    $product->image = Product::where('id','=',$product_id_a)->value('image');
                    $product->product_image_gallery = Product::where('id','=',$product_id_a)->value('product_image_gallery');
                    $product->price = Product::where('id','=',$product_id_a)->value('price');
                    $product->sale_price = Product::where('id','=',$product_id_a)->value('sale_price');
                    $product->category_ids = Product::where('id','=',$product_id_a)->value('category_ids');
                    $product->thumbnail   = Product::where('id','=',$product_id_a)->value('thumbnail');
                    $product->images  = Product::where('id','=',$product_id_a)->value('images'); 
                    $product->unit = $request->to_uom_id;
                    $product->purchase_price  = Product::where('id','=',$product_id_a)->value('purchase_price');
                    $product->slug =  Product::where('id','=',$product_id_a)->value('code');
                    $product->code =  Product::where('id','=',$product_id_a)->value('code');
                    $product->tax_percentage = 0;
                    $product->attributes = '[]';
                    $product->added_by = 'seller';
                    $product->brand_id = 1;
                    $product->product_type = 'physical';
                    $product->discount = 0;
                    $product->discount_type = 'flat';
                    $product->minimum_order_qty = 1;
                    $product->free_shipping = 0;
                    $product->colors = '[]';
                    $product->featured_status = 1;
                    $product->request_status = 1;
                    $product->published = 1;
                    $product->variation = '[]';
                    $product->choice_options = '[]';
                    $product->refundable = 1;
                    $product->min_qty = 1 ;
                    $product->meta_image = 'def.png';
                    $product->color_image = '[]';
                    $product->tax = 0;
                    $product->tax_type = 'percent';
                    $product->tax_model = 'exclude';
                    $product->shipping_cost = 0;
                    $product->multiply_qty = 0;
                    $product->status = 'publish';
                    //end api

                    // $items1 = collect($request->items);
                    // foreach($items1 as $item){
                    //     $product->minimum_stock = $item['product_id'];
                    // }
                    
                    // $items1 = collect($request->items);
                    // foreach($items1 as $item){
                    //     // $product->minimum_stock = $item['product_id'];
                    //     $productItem = new ProductItem;
                    //     $productItem->product_id = $product->id;
                    //     $productItem->vendor_id = 0;
                    // }

                    $items1 = collect($request->items);
                    $product_id_x = 0;

                    foreach($items1 as $item){
                        $product_id_x = $item['product_id'];
                    }
                    $get_price = Product::where('id','=',$product_id_x)->value('unit_price');
                    $get_uom = Product::where('id','=',$product_id_x)->value('uom');
                    $get_currency = Product::where('id','=',$product_id_x)->value('currency_id');
                    $get_vendor = ProductItem::where('product_id','=',$product_id_x)->value('vendor_id');
                    $get_category = Product::where('id','=',$product_id_x)->value('category_id');
                    $get_sub_category = Product::where('id','=',$product_id_x)->value('sub_category_id');
                    $get_name = Product::where('id','=',$product_id_x)->value('description');
                  //   $get_code = Product::where('id','=',$product_id_x)->value('code');
                    $current_stock = Product::where('id','=',$product_id_x)->value('current_stock');

                    foreach($items1 as $item){

                        $deleteProduct =  DB::table('products')
                        ->latest()->take(1)
                        ->where('id','=',$item['product_id'])
                        ->delete();

                        $deleteProductItem =  DB::table('product_items')
                        ->latest()->take(1)
                        ->where('product_id','=',$item['product_id'])
                        ->delete();
                    }

                    $price1 = $items1->sum(function($item) {
                        return $item['product_price'] ;
                    });

                    $count1 = $items1->count(function($item) {
                        return $item['product_price'] ;
                    });
                    
                    $product->unit_price = $price1 / $count1 ;
                    $product->save();

                    counter()->increment('product');
                    
               
                    $new_code = counter()->next('product');
                  $stock_movement = new StockMovement();
                  $stock_movement->user_id = auth()->id();
                  $stock_movement->product_id = $item['product_id'];
                  $stock_movement->product_code = $new_code;
                  $stock_movement->product_name = $request->description;
                  $stock_movement->warehouse_id = $request->warehouse_id;
                  $stock_movement->category_id = $request->category_id;
                  $stock_movement->sub_category_id = $request->sub_category_id;
                  $stock_movement->vendor_id = $get_vendor;
                  $stock_movement->qty =  $request->current_stock;
                  $stock_movement->uom = $get_uom;
                  $stock_movement->price = $price1 / $count1;
                  $stock_movement->currency = $request->currency_id;
                  $stock_movement->type = "Aggregation Product Stock";
                  $stock_movement->created_by = Auth::user()->name;
                  $stock_movement->save();

             

                    return $model;
                });
        
                return api([
                    'saved' => true,
                    'id' => $result->id
                ]);
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
        $user = auth()->user();
        if ($user->is_productsaggregation_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = ProductsAggregation::with([
                'currency', 'items.currency', 'items.vendor', 'items.uom', 'items.product', 'uom','warehouse','category','sub_category'
                ])->findOrFail($id);

            return api([
                'data' => $data
            ]);
        }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_productsaggregation_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        // $data = Product::with(['currency', 'items.currency', 'items.vendor', 'taxes','uom','warehouse','category','sub_category','values','attributes.attribute_name'])
        //     ->findOrFail($id);
        $customPaper = array(0,0,250,491);
        $products = ProductsAggregation::with(['currency', 'items.currency', 'items.vendor', 'items.uom', 'items.product', 'uom','warehouse','category','sub_category'])->findOrFail($id);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper($customPaper , 'landscape')->setWarnings(false)->loadView('docs.product_label', compact('products'));
        return View('docs.product_label', compact('products'));
        }
         //  return $pdf->download('product_label.pdf');
        // $doc  = 'docs.product_label';
        // return pdf($doc, $data);
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
        
        $user = auth()->user();
        if ($user->is_productsaggregation_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = ProductsAggregation::findOrFail($id);


            $deleteProduct =DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$model->code)
            ->delete();


            $get_price = Product::where('id','=',$model->code)->value('unit_price');
            $get_uom = Product::where('id','=',$model->code)->value('uom');
            $get_currency = Product::where('id','=',$model->code)->value('currency_id');
            $get_vendor = ProductItem::where('product_id','=',$model->code)->value('vendor_id');
            $get_category = Product::where('id','=',$model->code)->value('category_id');
            $get_warehouse = Product::where('id','=',$model->code)->value('warehouse_id');
            $get_sub_category = Product::where('id','=',$model->code)->value('sub_category_id');
            $get_name = Product::where('id','=',$model->code)->value('description');
            $get_code = Product::where('id','=',$model->code)->value('code');
            $current_stock = Product::where('id','=',$model->code)->value('current_stock');

            $stock_movement = new StockMovement();
            $stock_movement->user_id = auth()->id();
            $stock_movement->product_id = $model->code;
            $stock_movement->product_code = $get_code;
            $stock_movement->product_name = $get_name;
            $stock_movement->warehouse_id = $get_warehouse;
            $stock_movement->category_id = $get_category;
            $stock_movement->sub_category_id = $get_sub_category;
            $stock_movement->vendor_id = $get_vendor;
            $stock_movement->qty =  $current_stock;
            $stock_movement->uom = $get_uom;
            $stock_movement->price = $get_price;
            $stock_movement->currency = $get_currency;
            $stock_movement->type = "Delete Aggregation Product Stock";
            $stock_movement->created_by = Auth::user()->name;
            $stock_movement->save();


            $model->items()->delete();
            $model->delete();

            return api([
                'deleted' => true
            ]);
        }
    }
}
