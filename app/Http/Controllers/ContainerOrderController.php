<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContainerOrder\ContainerOrder;
use App\ContainerOrder\Product as ProductItem;

use App\PurchaseOrder\PurchaseOrder;
use App\PurchaseOrder\Item as PurchaseOrderItem;

use App\PurchaseOrder\Tax;
use App\Vendor;
use DB;
use Auth;
use App\DeliveryCondition;
use App\PaymentCondition;
use App\ExchangeRate\ExchangeRate;

use App\Mail\PurchaseOrders\Send;
use App\Mail\PurchaseOrders\Confirmed;
use App\Mail\PurchaseOrders\Declined;
use App\Settings;
use Mail;
use App\User;
use App\Notifications;

class ContainerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => ContainerOrder::with(['shipper', 'currency'])->search()
        ]);
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_Shipments_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->get();
        foreach($exchange as $rate1){
        $form = [
            'vendor_id' => null,
            'vendor' => null,
            'number' => counter()->next('container_order'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'terms' => null,
            'paymentcondition_id' => null,
            'deliverycondition_id' => null,
            'exchangerate' => $rate1->value2,
            'shipping'=> 0,
            'discount'=> 0,
            'items' => [
                [
                    'product' => null,
                    'product_id' => null,
                    'vendor_reference' => null,
                    'unit_price' => 0,
                    'quantity' => 1,
                    'uom'=> 0,
                    
                    'products' => []
                ]
            ]
        ];
    }
        if($request->has('vendor_id')) {
            $vendor = Vendor::with(['currency'])->findOrFail($request->vendor_id);

            array_set($form, 'vendor_id', $vendor->id);
            array_set($form, 'vendor', $vendor);
            array_set($form, 'currency_id', $vendor->currency->id);
            array_set($form, 'currency', $vendor->currency);

        } else {
            $form = array_merge($form, currency()->defaultToArray());
        }

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
        if ($user->is_Shipments_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'shipper_id' => 'required|integer|exists:shippers,id',
        ]);

        $model = new ContainerOrder();
        $model->fill($request->except('items'));

        $model->user_id = auth()->id();
        $model->status_id = ContainerOrder::DRAFT;
        $username = Auth::user()->name;
        
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;

        $model ->created_by = $username;
        $items = collect($request->items);
        // $model->subtotal = $items->sum(function($item) {
        //     return $item['qty'] * $item['unit_price'];
        // });

        $model->total_weight = $items->sum(function($item) {
            return $item['weight_box'];
        });
        $model->total_volume = $items->sum(function($item) {
            return $item['volume_box'];
        });

        $model->total_qty = $items->sum(function($item) {
            return $item['quantity'];
        });

        $model->total = $items->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });


        $model->total = $items->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });


        $model->subtotal = $items->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

            $model->totaltax = $items->sum(function($item) {
                return ($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100;
            });
   


        $model->total = $model->subtotal + $model->totaltax  - $request->discount + $request->shipping;

        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('container_order');
            $model->storeHasMany([
                'items' => $request->items,
                // 'products' => $request->products
               //'items.products' => $request->items
               
            ]);

            counter()->increment('container_order');

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
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
        if ($user->is_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => ContainerOrder::with(['items.product','items.uom', 'shipper', 'currency','paymentcondition','deliverycondition'])->findOrFail($id)
        ]);
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $user = auth()->user();
        if ($user->is_Shipments_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = ContainerOrder::with(['container_products','items','items.containerOrder','items.uom','items.uom2','items.product', 'shipper', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('container_order');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    break;

                case 'bill':
                    $form->container_order_id = $form->id;
                    $form->number = counter()->next('bill');
                    $form->date = date('Y-m-d');
                    $form->due_date = date('Y-m-d');
                    $form->reference = null;
                    $form->note = null;
                    $form->terms = null;
                    break;
                case 'receive_order':
                    // i
                    $form->container_order_id = $form->id;
                    $form->container_order_number = $form->number;
                    $form->number = counter()->next('container_receive_order');
                    $form->date = date('Y-m-d');
                    $form->note = null;
                    $items = $form->items->map(function($query) {
                        $query->quantity = $query->quantity - $query->qty_received;
                        if($query->quantity > 0) {
                            $query->qty_received = 0;
                            return $query;
                        }
                    })->reject(function($item) {
                        return is_null($item);
                    });
                    unset($form->items);
                    $form->items = $items;
                    break;
                default:
                    abort(404, 'Invalid Mode');
                    break;
            }
        } else {
            // abort if not editable
            abort_if(!$form->is_editable, 404);
        }

        return api([
            'form' => $form
        ]);
            }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_Shipments_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = ContainerOrder::with(['items.product','items.uom', 'shipper', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        $doc  = 'docs.container_orders';

        if($request->has('mode') && $request->mode == 'receive') {
            $doc = 'docs.receive';
            $items = $data->items->map(function($query) {
                $query->qty = $query->qty - $query->qty_received;
                if($query->qty > 0) {
                    $query->qty_received = 0;
                    return $query;
                }
            })->reject(function($item) {
                return is_null($item);
            });
            unset($data->items);
            $data->items = $items;
        }

        return pdf($doc, $data);}
    }


    public function markAs($id, Request $request)
    {
        $model = ContainerOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:1,2,3,5,6'
        ]);

        switch ($request->status) {
            case (ContainerOrder::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    ContainerOrder::DRAFT
                ]), 404);

                $model->request_date = date('Y-m-d');
                DB::table('container_orders')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = ContainerOrder::with(['items.product','items.uom', 'shipper', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'container_orders', $purchase_order
                    ));
                }

                $purchase_number = ContainerOrder::where('id','=',$id)->value('number');
                $purchase_notification = Settings::where('key','=','purchase_orders_notification')->value('value');
                if($purchase_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $purchase_number;
                    $notification->document_type = 'container';
                    $notification->description = 'New Container Order Created '.$purchase_number;
                    $notification->link = 'container_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'manager';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = ContainerOrder::SENT;
                break;

            case (ContainerOrder::CONFIRMED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    ContainerOrder::DRAFT,
                    ContainerOrder::SENT,
                    ContainerOrder::CANCELLED
                ]), 404);

                $model->confirmed_date = date('Y-m-d');
                DB::table('container_orders')
                    ->where('id', $id)
                    ->update(['confirmed_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = ContainerOrder::with(['items.product','items.uom', 'shipper', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Confirmed(
                        $request->only('to', 'bcc','subject', 'message'),
                        'container_orders', $purchase_order
                    ));
                }

                $purchase_number = ContainerOrder::where('id','=',$id)->value('number');
                $purchase_user = ContainerOrder::where('id','=',$id)->value('user_id');
                $purchase_notification = Settings::where('key','=','purchase_orders_notification')->value('value');
                if($purchase_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $purchase_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $purchase_number;
                    $notification->document_type = 'container_orders';
                    $notification->description = 'Container Order '.$purchase_number.' Approved';
                    $notification->link = 'container_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = ContainerOrder::CONFIRMED;

                $model->items->each(function($item) {
                    $product = $item->product;
                    $product->warehouse_qty = $product->warehouse_qty - $item->quantity;
                    $product->save();
                });

                break;

            case (ContainerOrder::CANCELLED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    ContainerOrder::DRAFT, ContainerOrder::SENT,
                    ContainerOrder::CONFIRMED
                ]), 404);

                $model->declined_date = date('Y-m-d');
                DB::table('container_orders')
                    ->where('id', $id)
                    ->update(['declined_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = ContainerOrder::with(['items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Declined(
                        $request->only('to', 'bcc','subject', 'message'),
                        'container_orders', $purchase_order
                    ));
                }

                $purchase_number = ContainerOrder::where('id','=',$id)->value('number');
                $purchase_user = ContainerOrder::where('id','=',$id)->value('user_id');
                $purchase_notification = Settings::where('key','=','purchase_orders_notification')->value('value');
                if($purchase_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $purchase_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $purchase_number;
                    $notification->document_type = 'container_orders';
                    $notification->description = 'Container Order '.$purchase_number.'  Declined';
                    $notification->link = 'container_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = ContainerOrder::CANCELLED;
                break;
            case (ContainerOrder::CLOSED) :
                // must be confirmed
                abort_if(!in_array($model->status_id, [
                    ContainerOrder::CONFIRMED
                ]), 404);

                $model->status_id = ContainerOrder::CLOSED;
                break;

            default:
                abort(404, 'Invalid Operation');
                break;
        }

        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id,
            'is_editable' => $model->is_editable,
            'status_id' => $model->status_id
        ]);
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
        if ($user->is_Shipments_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = ContainerOrder::findOrFail($id);

        // abort if not editable
        abort_if(!$model->is_editable, 404);

           
        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $items = collect($request->items);


        

        $model->total_weight = $items->sum(function($item) {
            return $item['weight_box'];
        });
        $model->total_volume = $items->sum(function($item) {
            return $item['volume_box'];
        });

        $model->total_qty = $items->sum(function($item) {
            return $item['quantity'];
        });

        $model->total = $items->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });


        $model->subtotal = $items->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

            $model->totaltax = $items->sum(function($item) {
                return ($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100;
            });
   


        $model->total = $model->subtotal + $model->totaltax  - $request->discount + $request->shipping;

        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items' => $request->items
                // 'items.taxes' => $request->items
            ]);

            return $model;
        });

        return api([
            'saved' => true,
            'id' => $model->id
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
        $user = auth()->user();
        if ($user->is_Shipments_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = ContainerOrder::findOrFail($id);
            $model->items()->delete();
            $model->delete();

            return api([
                'deleted' => true
            ]);
        }
    }
}
