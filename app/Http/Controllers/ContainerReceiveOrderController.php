<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContainerOrder\ContainerOrder;
use App\ContainerReceiveOrder\ContainerReceiveOrder;
use Exception;
use DB;
use Auth;
use App\StockMovement\StockMovement;
use App\Product\Product;
use App\Product\Item as ProductItem;

class ContainerReceiveOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_Receive_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ContainerReceiveOrder::with(['shipper'])->search()
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_Receive_Shipments_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $po = ContainerOrder::where('status_id', '>',2)->findOrFail($request->container_order_id);

        $this->validate($request, [
            'date' => 'required|date_format:Y-m-d',
            'document' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.qty_received' => 'required'
        ]);

        $model = new ContainerReceiveOrder();
        $model->fill($request->except('items'));
        $model->container_order_id = $po->id;
        $model->shipper_id = $po->shipper_id;
        $username = Auth::user()->name;
        $model ->created_by = $username;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        $model->user_id = auth()->id();
        $model->status_id = ContainerReceiveOrder::RECEIVED;

        $items = collect($request->items)->map(function($item) {
            if($item['qty_received'] > 0) {
                $item['container_order_item_id'] = $item['id'];
                $item['quantity'] = $item['qty_received'];
                


                $get_price = Product::where('id','=',$item['product_id'])->value('unit_price');
                $get_uom = Product::where('id','=',$item['product_id'])->value('uom');
                $get_currency = Product::where('id','=',$item['product_id'])->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$item['product_id'])->value('vendor_id');
                $get_category = Product::where('id','=',$item['product_id'])->value('category_id');
                $get_sub_category = Product::where('id','=',$item['product_id'])->value('sub_category_id');
                $get_warehouse = Product::where('id','=',$item['product_id'])->value('warehouse_id');
                $get_name = Product::where('id','=',$item['product_id'])->value('description');
                $get_code = Product::where('id','=',$item['product_id'])->value('code');
                // $current_stock = Product::where('id','=',$item['product_id'])->value('current_stock');
                $get_purchase_order = ContainerOrder::where('id','=',$item['id'])->value('number');

                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $item['product_id'];
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->warehouse_id = $get_warehouse;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $item['qty_received'];
                $stock_movement->uom = $get_uom;
                $stock_movement->price = $get_price ;
                $stock_movement->currency = $get_currency;
                $stock_movement->purchase_order_id = $item['id'];
                $stock_movement->purchase_order = $get_purchase_order;
                $stock_movement->type = "Container Receive Order Changed Stock";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        $model = DB::transaction(function() use ($model, $items, $po) {

            $model->number = counter()->next('container_receive_order');

            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update po and items

            $model->items->each(function($item) {
                $poItem = $item->ContainerOrderItem;
                $amount = $poItem->qty_received + $item->quantity;
                $poItem->qty_received = $amount;
                $poItem->save();

                $product = $item->product;
                $product->current_stock = $product->current_stock + $item->quantity;
                $product->status = 'publish';
               //  $product->warehouse_qty = $product->warehouse_qty + $item->qty;
                $product->save();
            });

            // po status
            $status = 7;
            foreach($po->items as $item) {
                if($item->qty_received < $item->quantity) {
                    $status = 8;
                }
            }

            $po->status_id = $status;
            $po->save();

            counter()->increment('container_receive_order');

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_Receive_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => ContainerReceiveOrder::with(['items.product', 'shipper', 'containerOrder'])->findOrFail($id)
        ]);
    }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_Receive_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = ContainerReceiveOrder::with(['items.product', 'shipper', 'containerOrder'])->findOrFail($id);

        $doc  = 'docs.container_receive_order';

        return pdf($doc, $data);
        }
    }
}
