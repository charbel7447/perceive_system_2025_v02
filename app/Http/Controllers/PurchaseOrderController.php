<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder\PurchaseOrder;
use App\PurchaseOrder\Tax;
use App\PurchaseOrder\Item as PurchaseOrderItem;
use App\Vendor;
use DB;
use Auth;
use App\DeliveryCondition;
use App\PaymentCondition;
use App\ExchangeRate\ExchangeRate;

use App\PurchaseOrder\PurchaseOrderLog;

use App\Mail\PurchaseOrders\Send;
use App\Mail\PurchaseOrders\Confirmed;
use App\Mail\PurchaseOrders\Declined;
use App\Settings;
use Mail;
use App\User;
use App\Notifications;
use App\PriceChanges;
use App\Services\JournalService;

class PurchaseOrderController extends Controller
{
          // Declare the property
    protected $journalService;

    // Inject JournalService in constructor
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }
    
    public function index()
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => PurchaseOrder::with(['vendor', 'currency'])->orderby('created_at','desc')->search()
        ]);
            }
    }

    public function search()
    {
        $results = PurchaseOrder::with('currency')
            ->orderBy('number')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'number','status_id','created_by','created_at']);

            return api([
            'results' => $results
        ]);
    }

    public function purchase_orders_sent()
    {
        $results = PurchaseOrder::with('currency')
            ->orderBy('number')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','=',2)
            ->limit(6)
            ->get();

            return api([
            'results' => $results
        ]);
    }
    
    

    public function container_purchase_orders()
    {
        $results = PurchaseOrder::with('currency','items','items.product','items.uom','items.vendor', 'vendor', 'currency')
            ->orderBy('number')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','>',2)
            ->limit(6)
            ->get(['id', 'number','status_id','created_by','created_at','total','total_qty','total_weight','total_volume']);

            return api([
            'results' => $results
        ]);
    }
    

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $purchase_order_field_1 = DB::table('settings')->where('key','=','purchase_order_field_1')->value('value');
        $purchase_order_field_2 = DB::table('settings')->where('key','=','purchase_order_field_2')->value('value');
        $purchase_order_field_3 = DB::table('settings')->where('key','=','purchase_order_field_3')->value('value');
        $purchase_order_field_4 = DB::table('settings')->where('key','=','purchase_order_field_4')->value('value');
        $form = [
            'vendor_id' => null,
            'vendor' => null,
            'number' => counter()->next('purchase_order'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'terms' => null,
            'paymentcondition_id' => null,
            'deliverycondition_id' => null,
            'shipping' => 0,
            'discount' => 0,
            'exchangerate' => $exchange,
            'line1_text' => $purchase_order_field_1,
            'line1_value' => 0,
            'line2_text' => $purchase_order_field_2,
            'line2_value' => 0,
            'line3_text' => $purchase_order_field_3,
            'line3_value' => 0,
            'line4_text' => $purchase_order_field_4,
            'line4_value' => 0,
            'items' => [
                [
                    'product' => null,
                    'product_id' => null,
                    'vendor_reference' => null,
                    'unit_price' => 0,
                    'qty' => 1,
                    'uom'=> 0,
                    'taxes' => []
                ]
            ]
        ];
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

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'paymentcondition_id' => 'required',
            'exchangerate' => 'nullable',
            'deliverycondition_id' => 'required',
            'vat_status' => 'nullable',
            'date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.vendor_reference' => 'required|alpha_dash|max:255',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'items.*.tax_name' => 'nullable',
            'items.*.tax_rate' => 'nullable',
            'items.*.taxes.*.name' => 'required|max:255',
            'items.*.taxes.*.rate' => 'required|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'required|max:255',
            'terms' => 'nullable|max:2000'
        ]);

        $model = new PurchaseOrder();
        $model->fill($request->except('items'));

        $model->user_id = auth()->id();
        $model->status_id = PurchaseOrder::DRAFT;
        $username = Auth::user()->name;
        
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;

        $model ->created_by = $username;
        $items = collect($request->items);
        $model->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        // $model->total_weight = $items->sum(function($item) {
        //     return $item['qty'] * $item['weight_box'];
        // });
        // $model->total_volume = $items->sum(function($item) {
        //     return $item['qty'] * $item['volume_box'];
        // });

        $model->total_qty = $items->sum(function($item) {
            return $item['qty'];
        });

        
        
        // $model->totaltax = $items->sum(function($item) {
        //     return ($item['qty'] * $item['unit_price'] * $item['tax_rate']) / 100;
        // });

           // add tax
        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['unit_price'] * $item['qty']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);

        if($model->vat_status == 1){
            $model->totaltax = $totalTax;
        }else{
            $model->totaltax= 0;
        }


        $model->total = $model->subtotal + $totalTax  - $request->discount + $request->shipping;
        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('purchase_order');
            $model->storeHasMany([
               // 'items' => $request->items
               'items.taxes' => $request->items
            ]);

            counter()->increment('purchase_order');

            return $model;
        });

        $purchase_log = new PurchaseOrderLog();
        $purchase_log->fill($request->except('items'));

        $purchase_log->user_id = auth()->id();
        $purchase_log->status_id = PurchaseOrderLog::DRAFT;
        $username = Auth::user()->name;
        
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $purchase_log->manager_id = $manager_id;

        $purchase_log ->created_by = $username;
        $items = collect($request->items);
        $purchase_log->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        // $purchase_log->total_weight = $items->sum(function($item) {
        //     return $item['qty'] * $item['weight_box'];
        // });
        // $purchase_log->total_volume = $items->sum(function($item) {
        //     return $item['qty'] * $item['volume_box'];
        // });

        $purchase_log->total_qty = $items->sum(function($item) {
            return $item['qty'];
        });

        $purchase_log->comment = "Store";
         $purchase_log->body = \App\PurchaseOrder\PurchaseOrder::where('id','=',$model->id)->get();
        $purchase_log->items = \App\PurchaseOrder\Item::where('purchase_order_id','=',$model->id)->get();
        
        if($purchase_log->vat_status == 1){
            $purchase_log->totaltax = $totalTax;
        }else{
            $purchase_log->totaltax= 0;
        }
        $purchase_log->total = $purchase_log->subtotal + $totalTax  - $request->discount + $request->shipping;
        $purchase_log = DB::transaction(function() use ($purchase_log, $request) {
            $purchase_log->number = counter()->next('purchase_order');
            $purchase_log->storeHasMany([
                'items' => $request->items
               //'items.taxes' => $request->items
            ]);
            return $purchase_log;
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
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => PurchaseOrder::with(['items.taxes','items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id)
        ]);
            }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = PurchaseOrder::with(['items.product','items.taxes', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        $doc  = 'docs.purchase_order';

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

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = PurchaseOrder::with(['items.taxes','items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('purchase_order');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    break;

                case 'bill':
                    $form->purchase_order_id = $form->id;
                    $form->number = counter()->next('bill');
                    $form->date = date('Y-m-d');
                    $form->due_date = null;
                    $form->reference = null;
                    $form->note = null;
                    $form->terms = null;
                    $form->vendor_id = $form->vendor_id;
                    break;
                case 'receive_order':
                    // i
                    $form->purchase_order_id = $form->id;
                    $form->purchase_order_number = $form->number;
                    $form->number = counter()->next('receive_order');
                    $form->date = date('Y-m-d');
                    $form->note = null;
                    $items = $form->items->map(function($query) {
                        $query->purchase_qty = $query->qty - $query->qty_received;
                        $query->uom_code = $query->uom_code;
                           $query->nb_of_lots = 0;
                        // $query->received_uom = $query->uom();
                        if($query->qty > 0) {
                            $query->qty_received = $query->qty - $query->qty_received;
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

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = PurchaseOrder::findOrFail($id);

        // abort if not editable
        abort_if(!$model->is_editable, 404);

        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'paymentcondition_id' => 'required',
            'deliverycondition_id' => 'required',
            'delivery_time' => 'nullable',
            'vat_status' => 'nullable',
            'exchangerate' => 'nullable',
            'date' => 'required|date_format:Y-m-d',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|integer|exists:purchase_order_items,id,purchase_order_id,'.$model->id,
            'items.*.product_id' => 'required|integer',
            'items.*.vendor_reference' => 'required|alpha_dash|max:255',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'items.*.tax_name' => 'nullable',
            'items.*.tax_rate' => 'nullable',
            'items.*.taxes.*.name' => 'nullable|max:255',
            'items.*.taxes.*.rate' => 'nullable|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'nullable|max:255',
            'terms' => 'nullable|max:2000'
        ]);
           
        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $items = collect($request->items);
        $model->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['unit_price'] * $item['qty']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);

        if($model->vat_status == 1){
            $model->totaltax = $totalTax;
        }else{
            $model->totaltax= 0;
        }
        

        // $model->total_weight = $items->sum(function($item) {
        //     return $item['qty'] * $item['weight_box'];
        // });
        // $model->total_volume = $items->sum(function($item) {
        //     return $item['qty'] * $item['volume_box'];
        // });

        $model->total_qty = $items->sum(function($item) {
            return $item['qty'];
        });



        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;

        $model->total = $model->subtotal + $totalTax  - $request->discount + $request->shipping;
       
        // $model->total = collect($request->items)->sum(function($item) {
        //     return $item['qty'] * $item['unit_price'];
        // });

        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                //'items' => $request->items
                'items.taxes' => $request->items
            ]);

            return $model;
        });

        $purchase_log = new PurchaseOrderLog();
        $purchase_log->fill($request->except('items'));

        $purchase_log->user_id = auth()->id();
        $purchase_log->status_id = PurchaseOrderLog::DRAFT;
        $username = Auth::user()->name;
        
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $purchase_log->manager_id = $manager_id;

        $purchase_log ->created_by = $username;
        $items = collect($request->items);
        $purchase_log->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        // $purchase_log->total_weight = $items->sum(function($item) {
        //     return $item['qty'] * $item['weight_box'];
        // });
        // $purchase_log->total_volume = $items->sum(function($item) {
        //     return $item['qty'] * $item['volume_box'];
        // });

        $purchase_log->total_qty = $items->sum(function($item) {
            return $item['qty'];
        });

        if($purchase_log->vat_status == 1){
            $purchase_log->totaltax = $totalTax;
        }else{
            $purchase_log->totaltax= 0;
        }
        $purchase_log->total = $purchase_log->subtotal + $totalTax  - $request->discount + $request->shipping;
        
          $purchase_log->comment = "Update";
         $purchase_log->body = \App\PurchaseOrder\PurchaseOrder::where('id','=',$model->id)->get();
        $purchase_log->items = \App\PurchaseOrder\Item::where('purchase_order_id','=',$model->id)->get();
        
        $purchase_log = DB::transaction(function() use ($purchase_log, $request) {
            $purchase_log->number = counter()->next('purchase_order');
            $purchase_log->storeHasMany([
                'items' => $request->items
               //'items.taxes' => $request->items
            ]);
            return $purchase_log;
        });
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }
    }

    public function markAs($id, Request $request)
    {
        $model = PurchaseOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:2,3,5,6,9'
        ]);

        switch ($request->status) {
            case (PurchaseOrder::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    PurchaseOrder::DRAFT
                ]), 404);

                $model->request_date = date('Y-m-d');
                DB::table('purchase_orders')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = PurchaseOrder::with(['items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'purchase_order', $purchase_order
                    ));
                }

                $purchase_number = PurchaseOrder::where('id','=',$id)->value('number');
                $purchase_notification = Settings::where('key','=','purchase_orders_notification')->value('value');
                if($purchase_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $purchase_number;
                    $notification->document_type = 'purchases';
                    $notification->description = 'New Purchase Order Created '.$purchase_number;
                    $notification->link = 'purchase_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'manager';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = PurchaseOrder::SENT;
                break;

            case (PurchaseOrder::CONFIRMED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    PurchaseOrder::DRAFT,
                    PurchaseOrder::SENT,
                    PurchaseOrder::CANCELLED
                ]), 404);

                $model->confirmed_date = date('Y-m-d');
                DB::table('purchase_orders')
                    ->where('id', $id)
                    ->update(['confirmed_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = PurchaseOrder::with(['items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Confirmed(
                        $request->only('to', 'bcc','subject', 'message'),
                        'purchase_order', $purchase_order
                    ));
                }
                
                //update vendor price
                $purchase_items = PurchaseOrderItem::where('purchase_order_id','=',$id)->get();
                foreach($purchase_items as $prItem){
                    $pr_product_id = $prItem->product_id;
                    $pr_vendor_id = $prItem->vendor_id;
                    $pr_unit_price = $prItem->unit_price;
                    
                    //adjusted via bill expenses
                     DB::table('product_items')
                        ->where('product_id', $pr_product_id)
                        ->where('vendor_id', $pr_vendor_id)
                        ->update(['price' => $pr_unit_price]);

                        DB::table('products')
                        ->where('id', $pr_product_id)
                        ->update(['sale_price' => $pr_unit_price]);
                    
                    $price_changes = new PriceChanges;
                    $price_changes->product_id = $pr_product_id;
                    $price_changes->unit_price = $pr_unit_price;

                    $price_changes->sale_price = $pr_unit_price;
                    $price_changes->comment = 'Purchase Order';
                    $price_changes->date = date('Y-m-d');
                    $price_changes->time = now();
                    $price_changes->save();
                
                }
             
                $purchase_number = PurchaseOrder::where('id','=',$id)->value('number');
                $purchase_user = PurchaseOrder::where('id','=',$id)->value('user_id');
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
                    $notification->document_type = 'purchases';
                    $notification->description = 'Purchase Order '.$purchase_number.' Approved';
                    $notification->link = 'purchase_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                if($model->posted != 1){
                    $documentData = $model->toArray();
                    $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'purchase_order');
                    if (!$journalVoucher) {
                        throw new \Exception ("PurchaseOrder saved but failed to create journal entries");
                    }else{
                        $model->posted = 0;
                        $journal_id = \App\PurchaseOrder\PurchaseOrder::where('id','=',$model->id)->value('journal_id');
                        $model->journal_id = $journal_id;
                    }
                }

                $model->status_id = PurchaseOrder::CONFIRMED;
                break;

            case (PurchaseOrder::CANCELLED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    PurchaseOrder::DRAFT, PurchaseOrder::SENT,
                    PurchaseOrder::CONFIRMED
                ]), 404);

                $model->declined_date = date('Y-m-d');
                DB::table('purchase_orders')
                    ->where('id', $id)
                    ->update(['declined_date' => date('Y-m-d')]);

                $purchase_email = Settings::where('key','=','purchase_orders_email')->value('value');
                if($purchase_email == 1){
                    $purchase_order = PurchaseOrder::with(['items.product','items.uom', 'vendor', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Declined(
                        $request->only('to', 'bcc','subject', 'message'),
                        'purchase_order', $purchase_order
                    ));
                }

                $purchase_number = PurchaseOrder::where('id','=',$id)->value('number');
                $purchase_user = PurchaseOrder::where('id','=',$id)->value('user_id');
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
                    $notification->document_type = 'purchases';
                    $notification->description = 'Purchase Order '.$purchase_number.'  Declined';
                    $notification->link = 'purchase_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = PurchaseOrder::CANCELLED;
                break;
            case (PurchaseOrder::REOPEN) :
                    // must be confirmed
                    abort_if(!in_array($model->status_id, [
                        PurchaseOrder::CONFIRMED
                    ]), 404);
    
                    if($model->posted == 1){
                        throw new \Exception ("Delete JV First");
                    }

                    $model->status_id = PurchaseOrder::DRAFT;
                    break;

                    
            case (PurchaseOrder::CLOSED) :
                // must be confirmed
                abort_if(!in_array($model->status_id, [
                    PurchaseOrder::CONFIRMED
                ]), 404);

                $model->status_id = PurchaseOrder::CLOSED;
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
            'posted' => $model->posted,
            'journal_id' => $model->journal_id,
            'status_id' => $model->status_id
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_purchaseorders_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = PurchaseOrder::findOrFail($id);

        $purchase_log = new PurchaseOrderLog();
        $purchase_log->reference = $id;
        $purchase_log->number = $model->number;
        $purchase_log->vendor_id = $model->vendor_id;
        $purchase_log->total = $model->total;
        $purchase_log->subtotal = $model->subtotal;
        $purchase_log->currency_id = $model->currency_id;
        $purchase_log->user_id = $model->user_id;
        $purchase_log->date = $model->date;
        $purchase_log->totaltax = $model->totaltax;
        $purchase_log->comment = \App\PurchaseOrder\Item::where('purchase_order_id','=',$id)->get();
          $purchase_log->comment = "Delete";
         $purchase_log->body = \App\PurchaseOrder\PurchaseOrder::where('id','=',$model->id)->get();
        $purchase_log->items = \App\PurchaseOrder\Item::where('purchase_order_id','=',$model->id)->get();
        $purchase_log->save();
      
        // check whether this particular purchase order belongs to
        $bills = $model->bills()->count();

        // if yes provide warning

        if($bills || !$model->is_editable) {
            return api([
                'message' => 'Delete all the purchase order relations first',
                'errors' => []
            ], 422);
        }

        $model->items()->delete();
        $model->delete();

        return api([
            'deleted' => true
        ]);
        }
    }

}
