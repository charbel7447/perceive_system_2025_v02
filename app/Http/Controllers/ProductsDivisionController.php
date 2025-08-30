<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Category;
use App\ProductsDivision;
use App\Product\Item;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\Uom;
use Auth;
use DB;
use \Milon\Barcode\DNS1D;
use PDF;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\StockMovement\StockMovement;

class ProductsDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_productsdivision_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Product::with(['currency','uom'])->search()
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_productsdivision_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
         //, 'items'
         $form = Product::with(['currency', 'category','sub_category', 'warehouse','uom','items','taxes'])
         ->findOrFail($id);
     if($request->has('mode')) {
         switch ($request->mode) {
              case 'clone':
 
                 $form->number = counter()->next('product');
                 $form->date = date('Y-m-d');
                 $form->reference = null;
                 break;
 
                 case 'division':
 
                     $form->number = counter()->next('product');
                     $form->date = date('Y-m-d');
                     $form->reference = null;
                     break;
                 
 
             default:
                 abort(404, 'Invalid Mode');
                 break;
         }
     }
 
     return api([
         'form' => $form
     ]);
    }
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
        if ($user->is_productsdivision_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $product = Product::findOrFail($id);
        $itemqty = Product::where('id','=',$id)->value('current_stock');

        $request->validate([
            'current_stock' => 'nullable',
            'to_current_stock' => 'nullable',
            'warehouse_id' => 'nullable',
            'unit' => 'nullable',
            'to_uom_id' => 'nullable',
            'vendor_id' => 'nullable',

        ]);

        $model = new ProductsDivision;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model->product_id = $request->id;
        $model->item_code = $request->code;
        $model->uom_id = $request->unit;
        $model->to_uom_id = $request->to_uom_id;
        $model->current_stock = $request->current_stock;
        $model->to_current_stock = $request->to_current_stock;
        $model->qty_on_hand = $request->current_stock;
        $model->to_qty_on_hand = $request->to_current_stock;
        $model->warehouse_id = $request->warehouse_id;
        $model ->created_by = $username;
       
        // only update if no qty in hand
        if($request->to_current_stock == $request->current_stock) {
            
             DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'uom' => $request->to_uom_id,
                    ]);
             DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'status' => 'U.O.M Changed',
                    ]);


                    $get_price = Product::where('id','=',$request->id)->value('unit_price');
                    $get_uom = Product::where('id','=',$request->id)->value('uom');
                    $get_currency = Product::where('id','=',$request->id)->value('currency_id');
                    $get_vendor = ProductItem::where('product_id','=',$request->id)->value('vendor_id');
                    $get_category = Product::where('id','=',$request->id)->value('category_id');
                    $get_sub_category = Product::where('id','=',$request->id)->value('sub_category_id');
                    $get_warehouse = Product::where('id','=',$request->id)->value('warehouse_id');
                    $get_name = Product::where('id','=',$request->id)->value('description');
                    $get_code = Product::where('id','=',$request->id)->value('code');
                    $current_stock = Product::where('id','=',$request->id)->value('current_stock');

                    $stock_movement = new StockMovement();
                    $stock_movement->user_id = auth()->id();
                    $stock_movement->product_id = $request->id;
                    $stock_movement->product_code = $get_code;
                    $stock_movement->product_name = $get_name;
                    $stock_movement->warehouse_id = $get_warehouse;
                    $stock_movement->category_id = $get_category;
                    $stock_movement->sub_category_id = $get_sub_category;
                    $stock_movement->vendor_id = $get_vendor;
                    $stock_movement->qty =  $current_stock;
                    $stock_movement->uom = $request->to_uom_id;
                    $stock_movement->price = $get_price ;
                    $stock_movement->currency = $get_currency;
                    $stock_movement->type = "Division/Addition U.O.M Changed Stock";
                    $stock_movement->created_by = Auth::user()->name;
                    $stock_movement->save();

        }
        if($request->current_stock > $request->to_current_stock) {
            if($itemqty == $request->current_stock)
            {
                 DB::table('products')
                ->latest()->take(1)
                ->where('id','=',$id)
                ->update([
                        'status' => 'Addition Product',
                        ]);

                 DB::table('products')
                ->latest()->take(1)
                ->where('id','=',$id)
                ->update([
                        'current_stock' => $request->current_stock,
                        ]);

                 DB::table('products')
                ->latest()->take(1)
                ->where('id','=',$id)
                ->update([
                        'uom' => $request->to_uom_id,
                        ]);


                //1st item stock movment
                $get_price = Product::where('id','=',$id)->value('unit_price');
                $get_uom = Product::where('id','=',$id)->value('uom');
                $get_currency = Product::where('id','=',$id)->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$id)->value('vendor_id');
                $get_category = Product::where('id','=',$id)->value('category_id');
                $get_sub_category = Product::where('id','=',$id)->value('sub_category_id');
                $get_name = Product::where('id','=',$id)->value('description');
                $get_code = Product::where('id','=',$id)->value('code');
                $current_stock = Product::where('id','=',$id)->value('current_stock');
                $warehouse_id = Product::where('id','=',$id)->value('warehouse_id');

                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $id;
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->warehouse_id = $warehouse_id;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $request->current_stock;
                $stock_movement->uom = $request->to_uom_id;
                $stock_movement->price = $get_price ;
                $stock_movement->currency = $get_currency;
                $stock_movement->type = "Division/Addition Change U.O.M Stock";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

            }else{
                 DB::table('products')
                ->latest()->take(1)
                ->where('id','=',$id)
                ->update([
                        'status' => 'Addition Product',
                        ]);

                 DB::table('products')
                ->latest()->take(1)
                ->where('id','=',$id)
                ->update([
                        'current_stock' => $itemqty - $request->current_stock,
                        ]);

                //1st item stock movment
                $get_price = Product::where('id','=',$id)->value('unit_price');
                $get_uom = Product::where('id','=',$id)->value('uom');
                $get_currency = Product::where('id','=',$id)->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$id)->value('vendor_id');
                $get_category = Product::where('id','=',$id)->value('category_id');
                $get_sub_category = Product::where('id','=',$id)->value('sub_category_id');
                $get_name = Product::where('id','=',$id)->value('description');
                $get_code = Product::where('id','=',$id)->value('code');
                $current_stock = Product::where('id','=',$id)->value('current_stock');

                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $id;
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->warehouse_id = $request->to_warehouse_id;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $itemqty - $request->current_stock;
                $stock_movement->uom = $request->to_uom_id;
                $stock_movement->price = $get_price ;
                $stock_movement->currency = $get_currency;
                $stock_movement->type = "Division/Addition Addition Stock";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();


                //create the rest in one product
                $newProduct = new Product;
                $newProduct->user_id = auth()->id();
                $username = Auth::user()->name;
                $newProduct ->created_by = $username;
                $newProduct->current_stock = $request->to_current_stock; 
                $newProduct->description = $request->description; 
                $newProduct->uom_id = $request->to_uom_id;
                $newProduct->warehouse_id = $request->warehouse_id; 
                $newProduct->category_id = $request->category_id; 
                $newProduct->sub_category_id = $request->sub_category_id; 
                $newProduct->unit_price = $request->unit_price;
                $newProduct->currency_id = $request->currency_id; 
                $newProduct->status = 'Manually Product Addition';
                //api
                $newProduct->title = $request->description; 
                $newProduct->summary = $request->description;
                $newProduct->name = $request->description;
                $newProduct->tag = $request->description;
                $newProduct->details = $request->description;
                $newProduct->sub_categoryid = Product::where('id','=',$id)->value('sub_categoryid');
                $newProduct->sub_sub_categoryid = Product::where('id','=',$id)->value('sub_sub_categoryid');
                $newProduct->sub_sub_category_id = Product::where('id','=',$id)->value('sub_sub_category_id');
                $newProduct->image = Product::where('id','=',$id)->value('image');
                $newProduct->product_image_gallery = Product::where('id','=',$id)->value('product_image_gallery');
                $newProduct->price = Product::where('id','=',$id)->value('price');
                $newProduct->sale_price = Product::where('id','=',$id)->value('sale_price');
                $newProduct->category_ids = Product::where('id','=',$id)->value('category_ids');
                $newProduct->thumbnail   = Product::where('id','=',$id)->value('thumbnail');
                $newProduct->images  = Product::where('id','=',$id)->value('images'); 
                $newProduct->unit = $request->to_uom_id;
                $newProduct->purchase_price  = Product::where('id','=',$id)->value('purchase_price');
                $newProduct->slug =  Product::where('id','=',$id)->value('code');
                $newProduct->code =  Product::where('id','=',$id)->value('code');
                $newProduct->tax_percentage = 0;
                $newProduct->attributes = '[]';
                $newProduct->added_by = 'seller';
                $newProduct->brand_id = 1;
                $newProduct->product_type = 'physical';
                $newProduct->discount = 0;
                $newProduct->discount_type = 'flat';
                $newProduct->minimum_order_qty = 1;
                $newProduct->free_shipping = 0;
                $newProduct->colors = '[]';
                $newProduct->featured_status = 1;
                $newProduct->request_status = 1;
                $newProduct->published = 1;
                $newProduct->variation = '[]';
                $newProduct->choice_options = '[]';
                $newProduct->refundable = 1;
                $newProduct->min_qty = 1 ;
                $newProduct->meta_image = 'def.png';
                $newProduct->color_image = '[]';
                $newProduct->tax = 0;
                $newProduct->tax_type = 'percent';
                $newProduct->tax_model = 'exclude';
                $newProduct->shipping_cost = 0;
                $newProduct->multiply_qty = 0;
                $newProduct->status = 'publish';
                //end api
                $categoryCode = DB::table('categories')
                ->latest()->take(1)
                ->where('id','=',$request->category_id)
                ->value('number');

                $subcategoryCode = DB::table('sub_category')
                ->latest()->take(1)
                ->where('id','=',$request->sub_category_id)
                ->value('number');

                // $newProduct->code = $subcategoryCode.counter()->next('product');
                $new_code = $subcategoryCode.counter()->next('product');
                // $barcode = new BarcodeGenerator();
                // $barcode->setText($subcategoryCode.counter()->next('product'));
                // $barcode->setType(BarcodeGenerator::Code39);
                // $barcode->setScale(1,5);
                // $barcode->setThickness(25);
                // $barcode->setFontSize(10);
                // $code = $barcode->generate();
                // $newProduct->barcode = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
                $barcode = \DNS1D::getBarcodePNG($new_code, 'C39',1.05,40,array(1,1,1,1), true);
                $barcode = '<img class="barcode-img" src="data:image/png;base64,'.$barcode.'" />';
                // $newProduct->barcode = $barcode;


                counter()->increment('product');
        
                $newProduct->storeHasMany([
                    'items' => $request->items,
                    'taxes' => $request->taxes,
                ]);

                $newProduct ->save();


                  //1st item stock movment
                  $get_price = Product::where('id','=',$id)->value('unit_price');
                  $get_uom = Product::where('id','=',$id)->value('uom');
                  $get_currency = Product::where('id','=',$id)->value('currency_id');
                  $get_vendor = ProductItem::where('product_id','=',$id)->value('vendor_id');
                  $get_category = Product::where('id','=',$id)->value('category_id');
                  $get_sub_category = Product::where('id','=',$id)->value('sub_category_id');
                  $get_name = Product::where('id','=',$id)->value('description');
                //   $get_code = Product::where('id','=',$id)->value('code');
                  $current_stock = Product::where('id','=',$id)->value('current_stock');
  
                  $stock_movement = new StockMovement();
                  $stock_movement->user_id = auth()->id();
                  $stock_movement->product_id = $newProduct->id;
                  $stock_movement->product_code = $new_code;
                  $stock_movement->product_name = $request->description;
                  $stock_movement->warehouse_id = $request->to_warehouse_id;
                  $stock_movement->category_id = $request->get_category;
                  $stock_movement->sub_category_id = $request->sub_category_id;
                  $stock_movement->vendor_id = $get_vendor;
                  $stock_movement->qty =  $request->current_stock;
                  $stock_movement->uom = $request->to_uom_id;
                  $stock_movement->price = $request->unit_price;
                  $stock_movement->currency = $request->currency_id;
                  $stock_movement->type = "Manually Product Addition Stock";
                  $stock_movement->created_by = Auth::user()->name;
                  $stock_movement->save();
                
            }
                
        }
        if($request->current_stock < $request->to_current_stock) {
            
             DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'status' => 'publish',
                    ]);

             DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'current_stock' => $itemqty - $request->current_stock,
                    ]);

            for ($i = 0; $i < ($request->to_current_stock); $i++)
            {
                //Create new product with transfered quantities
            $newProduct = new Product;
            $newProduct->user_id = auth()->id();
            $username = Auth::user()->name;
            $newProduct ->created_by = $username;
            $newProduct->current_stock = 1; 

            //api
            $newProduct->title = $request->description; 
            $newProduct->summary = $request->description;
            $newProduct->name = $request->description;
            $newProduct->tag = $request->description;
            $newProduct->details = $request->description;
            $newProduct->sub_categoryid = Product::where('id','=',$id)->value('sub_categoryid');
            $newProduct->sub_sub_categoryid = Product::where('id','=',$id)->value('sub_sub_categoryid');
            $newProduct->sub_sub_category_id = Product::where('id','=',$id)->value('sub_sub_category_id');
            $newProduct->image = Product::where('id','=',$id)->value('image');
            $newProduct->product_image_gallery = Product::where('id','=',$id)->value('product_image_gallery');
            $newProduct->price = Product::where('id','=',$id)->value('price');
            $newProduct->sale_price = Product::where('id','=',$id)->value('sale_price');
            $newProduct->category_ids = Product::where('id','=',$id)->value('category_ids');
            $newProduct->thumbnail   = Product::where('id','=',$id)->value('thumbnail');
            $newProduct->images  = Product::where('id','=',$id)->value('images'); 
            $newProduct->unit = $request->to_uom_id;
            $newProduct->purchase_price  = Product::where('id','=',$id)->value('purchase_price');
            $newProduct->slug = Product::where('id','=',$id)->value('code');
            $newProduct->code = Product::where('id','=',$id)->value('code');
            $newProduct->tax_percentage = 0;
            $newProduct->attributes = '[]';
            $newProduct->added_by = 'seller';
            $newProduct->brand_id = 1;
            $newProduct->product_type = 'physical';
            $newProduct->discount = 0;
            $newProduct->discount_type = 'flat';
            $newProduct->minimum_order_qty = 1;
            $newProduct->free_shipping = 0;
            $newProduct->colors = '[]';
            $newProduct->featured_status = 1;
            $newProduct->request_status = 1;
            $newProduct->published = 1;
            $newProduct->variation = '[]';
            $newProduct->choice_options = '[]';
            $newProduct->refundable = 1;
            $newProduct->min_qty = 1 ;
            $newProduct->meta_image = 'def.png';
            $newProduct->color_image = '[]';
            $newProduct->tax = 0;
            $newProduct->tax_type = 'percent';
            $newProduct->tax_model = 'exclude';
            $newProduct->shipping_cost = 0;
            $newProduct->multiply_qty = 0;
            $newProduct->status = 'publish';
                //end api
            $newProduct->description = $request->description; 
            $newProduct->uom = $request->to_uom_id;
            $newProduct->warehouse_id = $request->warehouse_id; 
            $newProduct->category_id = $request->category_id; 
            $newProduct->sub_category_id = $request->sub_category_id; 
            $newProduct->unit_price = $request->unit_price;
            $newProduct->currency_id = $request->currency_id; 
            $newProduct->status = 'publish';
            $categoryCode = DB::table('categories')
            ->latest()->take(1)
            ->where('id','=',$request->category_id)
            ->value('number');

            $subcategoryCode = DB::table('sub_category')
            ->latest()->take(1)
            ->where('id','=',$request->sub_category_id)
            ->value('number');

            // $newProduct->code = $subcategoryCode.counter()->next('product');
            
            // $barcode = new BarcodeGenerator();
            // $barcode->setText($subcategoryCode.counter()->next('product'));
            // $barcode->setType(BarcodeGenerator::Code39);
            // $barcode->setScale(1,5);
            // $barcode->setThickness(25);
            // $barcode->setFontSize(10);
            // $code = $barcode->generate();
            // $newProduct->barcode = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
            $new_code = $subcategoryCode.counter()->next('product');
            $barcode = \DNS1D::getBarcodePNG($new_code, 'C39',1.05,40,array(1,1,1,1), true);
            $barcode = '<img class="barcode-img" src="data:image/png;base64,'.$barcode.'" />';
            // $newProduct->barcode = $barcode;

            counter()->increment('product');
      
            $newProduct->storeHasMany([
                'items' => $request->items,
                'taxes' => $request->taxes,
            ]);

            $newProduct ->save();


             //1st item stock movment
             $get_price = Product::where('id','=',$newProduct->id)->value('unit_price');
             $get_uom = Product::where('id','=',$newProduct->id)->value('uom');
             $get_currency = Product::where('id','=',$newProduct->id)->value('currency_id');
             $get_vendor = ProductItem::where('product_id','=',$newProduct->id)->value('vendor_id');
             $get_category = Product::where('id','=',$newProduct->id)->value('category_id');
             $get_warehouse = Product::where('id','=',$newProduct->id)->value('warehouse_id');
             $get_sub_category = Product::where('id','=',$newProduct->id)->value('sub_category_id');
             $get_name = Product::where('id','=',$newProduct->id)->value('description');
             $get_code = Product::where('id','=',$newProduct->id)->value('code');
             $current_stock = Product::where('id','=',$newProduct->id)->value('current_stock');

             $stock_movement = new StockMovement();
             $stock_movement->user_id = auth()->id();
             $stock_movement->product_id = $newProduct->id;
             $stock_movement->product_code = $new_code;
             $stock_movement->product_name = $request->description;
             $stock_movement->warehouse_id = $get_warehouse;
             $stock_movement->category_id = $get_category;
             $stock_movement->sub_category_id = $get_sub_category;
             $stock_movement->vendor_id = $get_vendor;
             $stock_movement->qty =  '1';
             $stock_movement->uom = $request->to_uom_id;
             $stock_movement->price = $request->unit_price;
             $stock_movement->currency = $request->currency_id;
             $stock_movement->type = "Manually Product Division Stock";
             $stock_movement->created_by = Auth::user()->name;
             $stock_movement->save();

            }
            
        }
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        
        ]);
        return redirect()->back();
        return redirect()->back();
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
