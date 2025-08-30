<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Category;
use App\Transfers;
use App\Product\Item;
use App\Product\Product;
use App\StockMovement\StockMovement;
use App\Uom;
use Auth;
use DB;
use \Milon\Barcode\DNS1D;
use PDF;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\Product\Item as ProductItem;
use \Milon\Barcode\DNS2D;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_transfers_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Product::with(['currency','uom'])->search()
            ]);
        }
    }

    public function search(Request $request)
    {
            $results = Product::with('taxes')
                ->orderBy('code')
                ->when(request('q'), function($query) {
                    $query->where('code', 'like', '%'.request('q').'%')
                        ->orWhere('description', 'like', '%'.request('q').'%');
                })
                ->limit(6)
                ->get(['id', 'code', 'description', 'unit_price']);
                return api([
                    'results' => $results
                ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {   
  
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
        if ($user->is_transfers_create == 0 && $user->is_admin != 1){
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

                case 'transfer':

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
        if ($user->is_transfers_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $product = Product::findOrFail($id);
        $itemqty = Product::where('id','=',$id)->value('current_stock');
        $uom = Product::where('id','=',$id)->value('uom');
        $request->validate([
            'current_stock' => 'nullable',
            'warehouse_id' => 'nullable',
            'to_warehouse_id' => 'nullable',
            'vendor_id' => 'nullable',

        ]);

        $model = new Transfers;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model->product_id = $request->id;
        $model->item_code = $request->code;
        $model->uom_id = $uom;
        $model ->created_by = $username;
        $model->description = $request->description;
        
        $qty = $itemqty;
        $product ->status = 'Transfer';
       
        // only update if no qty in hand
        if($qty == $request->current_stock) {
            
            DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'warehouse_id' => $request->to_warehouse_id,
                    ]);
            DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'status' => 'Manually Transfer',
                    ]);

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
                    $stock_movement->qty =  $current_stock;
                    $stock_movement->uom = $get_uom;
                    $stock_movement->price = $get_price ;
                    $stock_movement->currency = $get_currency;
                    $stock_movement->purchase_order = $model->id;
                    $stock_movement->type = "Manually Transfer Stock";
                    $stock_movement->created_by = Auth::user()->name;
                    $stock_movement->save();

        }
        if($request->current_stock == 0) {
            abort(404, 'Invalid Mode No More Quanity for this material');
            return api([
                'saved' => true,
                'id' => $result->id
            ]);
        }
        if($qty != $request->current_stock) {
            $qtyFinal = $qty - $request->current_stock;
            DB::table('products')
            ->latest()->take(1)
            ->where('id','=',$id)
            ->update([
                     'current_stock' => $qtyFinal,
                    ]);

            
            //Create new product with transfered quantities
            $newProduct = new Product;
            $newProduct->user_id = auth()->id();
            $username = Auth::user()->name;
            $newProduct ->created_by = $username;
            $newProduct->current_stock = $request->current_stock; 
            $newProduct->description = $request->description; 
            $newProduct->uom_id = $get_uom;
            $newProduct->warehouse_id = $request->to_warehouse_id; 
            $newProduct->category_id = $request->category_id; 
            $newProduct->sub_category_id = $request->sub_category_id; 
            $newProduct->unit_price = $request->unit_price;
            $newProduct->currency_id = $request->currency_id; 
            $newProduct->status = 'Manually Transfer';
            $categoryCode = DB::table('categories')
            ->latest()->take(1)
            ->where('id','=',$request->category_id)
            ->value('number');

            $subcategoryCode = DB::table('sub_category')
            ->latest()->take(1)
            ->where('id','=',$request->sub_category_id)
            ->value('number');

            $newProduct->code = $subcategoryCode.counter()->next('product');
            
            $new_code = $subcategoryCode.counter()->next('product');

            // $barcode = new BarcodeGenerator();
            // $barcode->setText($subcategoryCode.counter()->next('product'));
            // $barcode->setType(BarcodeGenerator::Code39);
            // $barcode->setScale(1,5);
            // $barcode->setThickness(25);
            // $barcode->setFontSize(10);
            // $code = $barcode->generate();
            // $newProduct->barcode = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';

            $code = \DNS1D::getBarcodePNG($subcategoryCode.counter()->next('product'), 'C39',1.05,40,array(1,1,1,1), true);
            $code = '<img class="barcode-img" src="data:image/png;base64,'.$code.'" />';
            $newProduct->barcode = $code;


            counter()->increment('product');
      
            $newProduct->storeHasMany([
                'items' => $request->items,
                'taxes' => $request->taxes,
            ]);

            $newProduct ->save();


            $get_price = Product::where('id','=',$id)->value('unit_price');
            $get_uom = Product::where('id','=',$id)->value('uom');
            $get_currency = Product::where('id','=',$id)->value('currency_id');
            $get_vendor = ProductItem::where('product_id','=',$id)->value('vendor_id');
            $get_category = Product::where('id','=',$id)->value('category_id');
            $get_sub_category = Product::where('id','=',$id)->value('sub_category_id');
            $get_name = Product::where('id','=',$id)->value('description');
            $get_code = Product::where('id','=',$id)->value('code');
            
            $stock_movement = new StockMovement();
            $stock_movement->user_id = auth()->id();
            $stock_movement->product_id = $id;
            $stock_movement->product_code = $new_code;
            $stock_movement->product_name = $request->description;
            $stock_movement->warehouse_id = $request->to_warehouse_id;
            $stock_movement->category_id = $get_category;
            $stock_movement->sub_category_id = $get_sub_category;
            $stock_movement->vendor_id = $get_vendor;
            $stock_movement->qty =  $request->current_stock;
            $stock_movement->uom = $get_uom;
            $stock_movement->price = $request->unit_price ;
            $stock_movement->currency = $request->currency_id;
            $stock_movement->purchase_order = $model->id;
            $stock_movement->type = "Partially Transfer Stock";
            $stock_movement->created_by = Auth::user()->name;
            $stock_movement->save();

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
