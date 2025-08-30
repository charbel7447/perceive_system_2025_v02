<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice\Invoice;
use App\CustomerReturn\CustomerReturn;
use Exception;
use DB;
use Auth;
use App\StockMovement\StockMovement;
use App\Product\Product;
use App\Product\Item as ProductItem;

class CustomerReturnsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => CustomerReturn::with(['client'])->search()
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_receiveorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $po = Invoice::where('status_id','>',1)
            ->findOrFail($request->invoice_id);

        $this->validate($request, [
            'date' => 'required|date_format:Y-m-d',
            'document' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:invoice_items,id,invoice_id,'.$po->id,
            // 'items.*.qty_returned' => 'required|numeric|min:0|invoice_item:items.*.id'
        ]);

     
        $model = new CustomerReturn();
        $model->fill($request->except('items'));
        $model->invoice_id = $po->id;
        $model->client_id = $po->client_id;
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
        $model->status_id = CustomerReturn::RETURNED;

        $items = collect($request->items)->map(function($item) {
            if($item['qty_returned'] > 0) {
                $item['invoice_item_id'] = $item['id'];
                $item['returned_qty'] = $item['qty_returned'];
                $item['invoiced_qty'] = $item['qty'];

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
                $get_purchase_order = Invoice::where('id','=',$item['id'])->value('number');

                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $item['product_id'];
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->warehouse_id = $get_warehouse;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $item['qty_returned'];
                $stock_movement->uom = $get_uom;
                $stock_movement->price = $get_price ;
                $stock_movement->currency = $get_currency;
                $stock_movement->purchase_order_id = $item['id'];
                $stock_movement->purchase_order = $get_purchase_order;
                $stock_movement->type = "Returned Qty";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        $model = DB::transaction(function() use ($model, $items, $po) {

            $model->number = counter()->next('customer_returns');

            $model->storeHasMany([
                'items' => $items
            ]);

            $model->items->each(function($item) {
               
                    $product_stock = Product::where('id','=',$item['item_id'])->value('current_stock');
                    $poItem = $item->InvoiceItem;
                  
    
                    $invoice_qty = \App\Invoice\Item::where('invoice_id','=',$poItem->invoice_id)->where('item_id','=',$item['item_id'])->value('quantity');
                    $invoice_qty_returned = \App\Invoice\Item::where('invoice_id','=',$poItem->invoice_id)->where('item_id','=',$item['item_id'])->value('invoice_qty_returned');
                    DB::table('test4')
                    ->where('id', '=', 1)
                    ->update(['body' => $item['returned_qty']]); 
            

                 
                    if($invoice_qty == $invoice_qty_returned){
                        return response()->json(['error' => 'Forbidden.'], 403);

                        DB::table('test4')
                        ->where('id', '=', 2)
                        ->update(['body' => $model->id]);

                    }else{
                        if($invoice_qty < $item['qty_returned']){
                            return response()->json(['error' => 'Forbidden.'], 403);
                            DB::table('test4')
                            ->where('id', '=', 3)
                            ->update(['body' => $model->id]);
                        }else{
                            Product::where('id','=',$item['item_id'])->update(['current_stock' => $product_stock + $item['qty_returned'] ]);
    
                            $poItem = $item->InvoiceItem;
                            $amount = $poItem->invoice_qty_returned + $item['qty_returned'];
                            $poItem->invoice_qty_returned = $amount;
                            $poItem->save();

                            if($invoice_qty == ($amount )){
                                \App\Invoice\Invoice::where('id', '=',$poItem->invoice_id)
                                ->update(['status_id' => 10]);
                            }else{
                                \App\Invoice\Invoice::where('id', '=',$poItem->invoice_id)
                                ->update(['status_id' => 2]);
                            }
                           
                        }
                    }

            });


          
            counter()->increment('customer_returns');

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
            'data' => CustomerReturn::with(['items.product', 'client', 'invoiceOrder'])->findOrFail($id)
        ]);
    }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_receiveorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = CustomerReturn::with(['items.product', 'client', 'invoiceOrder'])->findOrFail($id);

        $doc  = 'docs.customer_return';

        return pdf($doc, $data);
        }
    }
}
