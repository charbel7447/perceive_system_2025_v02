<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder\PurchaseOrder;
use App\ReceiveOrder\ReceiveOrder;
use Exception;
use DB;
use Auth;
use App\StockMovement\StockMovement;
use App\Product\Product;
use App\Product\Item as ProductItem;

class ReceiveOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ReceiveOrder::with(['vendor'])->orderby('created_at','desc')->search()
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_receiveorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $po = PurchaseOrder::whereIn('status_id', [
                PurchaseOrder::CONFIRMED,
                PurchaseOrder::PARTIALLY_RECEIVED
            ])
            ->findOrFail($request->purchase_order_id);

        $this->validate($request, [
            'date' => 'required|date_format:Y-m-d',
            'document' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:purchase_order_items,id,purchase_order_id,'.$po->id,
            // 'items.*.qty_received' => 'required|numeric|min:0|purchase_order_item:items.*.id'
            'items.*.qty_received' => 'required',
            // 'items.*.nb_of_lots' => 'required',
            'items.*.received_uom_id' => 'required'
        ]);

        $model = new ReceiveOrder();
        $model->fill($request->except('items'));
        $model->purchase_order_id = $po->id;
        $model->vendor_id = $po->vendor_id;
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
        $model->status_id = ReceiveOrder::RECEIVED;

        $items = collect($request->items)->map(function($item) {
            if($item['qty_received'] > 0) {
                $item['purchase_order_item_id'] = $item['id'];
                $item['uom_id'] = $item['uom_id'];
                $item['uom_code'] = $item['uom_code'];
                $item['received_uom_id'] = $item['received_uom_id'];
                $item['received_uom_unit'] = $item['received_uom_unit'];
                $item['received_uom_code'] = $item['received_uom_code'];
                $item['qty'] = $item['qty_received'];
                $item['purchase_qty'] = $item['purchase_qty'];
                // $item['nb_of_lots'] = $item['nb_of_lots'];
                


                $get_price = Product::where('id','=',$item['product_id'])->value('unit_price');
                $get_uom = Product::where('id','=',$item['product_id'])->value('unit');
                $get_currency = Product::where('id','=',$item['product_id'])->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$item['product_id'])->value('vendor_id');
                $get_category = Product::where('id','=',$item['product_id'])->value('category_id');
                $get_sub_category = Product::where('id','=',$item['product_id'])->value('sub_category_id');
                $get_warehouse = Product::where('id','=',$item['product_id'])->value('warehouse_id');
                $get_name = Product::where('id','=',$item['product_id'])->value('description');
                $get_code = Product::where('id','=',$item['product_id'])->value('code');
                // $current_stock = Product::where('id','=',$item['product_id'])->value('current_stock');
                $get_purchase_order = PurchaseOrder::where('id','=',$item['id'])->value('number');

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
                $stock_movement->type = "Receive Order Changed Stock";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        $model = DB::transaction(function() use ($model, $items, $po) {

            $model->number = counter()->next('receive_order');
            $shipping_process = \App\Vendor::where('id','=',$model->vendor_id)->value('shipping_process');

            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update po and items
            if($shipping_process == 0){
            $model->items->each(function($item)  use ($model) {
                $poItem = $item->purchaseOrderItem;

                if($item->uom_id != $item->received_uom_id){
                   
                    $conversion_factor = \App\Product\Conversion::where('product_id','=',$item->product_id)
                    ->where('converted_uom_id','=',$item->received_uom_id)->value('converted_qty');

                    $converted_qty = round($item->qty / $conversion_factor);
                    //   throw new \Exception($item->purchase_qty .' / '. $converted_qty);
                    if($converted_qty > $item->purchase_qty){
                        throw new \Exception('Variation in stock after conversion factor');
                    }else{
                        $amount = $poItem->qty_received + $converted_qty;
                    }
                    
                }else{
                    $amount = $poItem->qty_received + $item->qty;
                }

                
                $poItem->qty_received = $amount;
                $poItem->save();

                $product = $item->product;
          
                // throw new \Exception($item->received_uom_id);
               $product_lot_qty = $product->lot_qty;
            //    throw new \Exception($product->uom_id  . ' '.$item->received_uom_id);

                
                 // throw new \Exception($item->product_id);
                if($product->uom_id != $item->received_uom_id){
                    $conversion_factor = \App\Product\Conversion::where('product_id','=',$item->product_id)
                    ->where('converted_uom_id','=',$item->received_uom_id)->value('converted_qty');

                    $converted_qty = round($item->qty / $conversion_factor);
                    // if($item->qty < $converted_qty){
                    //     throw new \Exception('Variation in stock after conversion factor');
                    // }
                    $product->current_stock = $product->current_stock + $converted_qty;
                    $nb_of_lots = ceil($product->current_stock / $product_lot_qty);
                }else{
                    $product->current_stock = $product->current_stock + $item->qty;
                    $nb_of_lots = ceil($product->current_stock / $product_lot_qty);
                }

                
                // throw new \Exception ($model->vendor_id);
                for ($i = 0; $i < $nb_of_lots; $i++) {
                    $lots = new \App\Product\Lots();
                    $lots->product_id = $product->id;
                    $lots->product_name = $product->description;
                    $lots->uom_id = $product->uom_id;
                    $lots->vendor_id = $model->vendor_id;
                    $code = $product->id . '' . counter()->next('product_lots');
                    $lots->code = $code;
                    $lots->barcode = \DNS1D::getBarcodePNG($code, 'C39', 1, 30);

                    // Handle last lot case (may have less than $product_lot_qty)
                    if ($i === $nb_of_lots - 1 && $product->current_stock % $product_lot_qty !== 0) {
                        $lots->qty = $product->current_stock % $product_lot_qty;
                        $lots->balance = $product->current_stock % $product_lot_qty;
                    } else {
                        $lots->qty = $product_lot_qty;
                        $lots->balance = $product_lot_qty;
                    }

                    $lots->receive_order_id = $item['id'];
                    $lots->save();

                    counter()->increment('product_lots');
                }

                //$product->product_nb_of_lots = $product_nb_of_lots  + $item->product_nb_of_lots;

                // $product->current_stock = $product->current_stock + $item->qty;
                // $product->warehouse_qty = $product->warehouse_qty + $item->qty;
                // $product->current_stock = $product->current_stock + $item->qty;
                // $product->warehouse_qty = $product->warehouse_qty + $item->qty;
                $product->save();
            });
            }
            if($shipping_process == 1){
            // $model->items->each(function($item) {
            //     $poItem = $item->purchaseOrderItem;
            //     $amount = $poItem->qty_received + $item->qty;
            //     $poItem->qty_received = $amount;
            //     $poItem->save();

            //     $product = $item->product;
            //     // $product->current_stock = $product->current_stock + $item->qty;
            //     $product->warehouse_qty = $product->warehouse_qty + $item->qty;
            //     // $product->current_stock = $product->current_stock + $item->qty;
            //     // $product->warehouse_qty = $product->warehouse_qty + $item->qty;
            //     $product->save();
            // });
            }
            // po status
            $status = PurchaseOrder::RECEIVED;
            foreach($po->items as $item) {
                if($item->qty_received < $item->qty) {
                    $status = PurchaseOrder::PARTIALLY_RECEIVED;
                }
            }

            $po->status_id = $status;
            $po->save();

            counter()->increment('receive_order');

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
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => ReceiveOrder::with(['items.product', 'vendor', 'purchaseOrder'])->findOrFail($id)
        ]);
    }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = ReceiveOrder::with(['items.product', 'vendor', 'purchaseOrder'])->findOrFail($id);

        $doc  = 'docs.receive_order';

        return pdf($doc, $data);
        }
    }

    public function markAs($id, Request $request)
    {
        $model = ReceiveOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:9'
        ]);

        DB::table('purchase_orders')
        ->where('id', $model->purchase_order_id)
        ->update(['status_id' => 3]);

        $items =  \App\ReceiveOrder\Item::where('receive_order_id','=',$id)->get();
        foreach($items as $item){
            $product_id = $item->product_id;
            $qty = $item->qty;
            $purchase_order_item_id = $item->purchase_order_item_id;

            $current_stock = Product::where('id', $product_id)
                ->value('current_stock');
            Product::where('id', $product_id)
                ->update(['current_stock' => $current_stock - $qty]);
            
            DB::table('purchase_order_items')
                ->where('purchase_order_id', $model->purchase_order_id)
                ->where('id', $purchase_order_item_id)
                ->update(['qty_received' => 0]);
            
        }

        $model->delete();
        $model->items()->delete();

        return api([
            'saved' => true,
            'id' => $model->id,
            'status_id' => $model->status_id,
            'is_editable' => $model->is_editable
        ]);

    }
}
