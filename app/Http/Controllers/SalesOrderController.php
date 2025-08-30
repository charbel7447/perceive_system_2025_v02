<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder\SalesOrder;
use App\SalesOrder\Item as SalesOrderItem;

use App\SalesOrder\SalesOrderLog;
use App\SalesOrder\ItemLog as SalesOrderItemLog;

use App\Quotation\Quotation;
use App\Client;
use App\PaymentCondition;
use App\DeliveryCondition;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\VatRate\VatRate;
use Carbon\Carbon;

use App\Mail\SalesOrders\Send;
use App\Mail\SalesOrders\Confirmed;
use App\Mail\SalesOrders\Declined;
use App\Settings;
use App\User;
use App\Notifications;
use Mail;
use App\SellerPayment\SellerPayment;
use App\Sellers\Sellers;

use Exception;
use App\Services\JournalService;

class SalesOrderController extends Controller
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => SalesOrder::with(['client', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }

    public function search()
    {
        $user = auth()->user();
        $results = SalesOrder::with(['client', 'currency','items','items.product_code'])->when(request('q'), function($query) {
             $query->where('code', 'like', '%'.request('q').'%')
                        ->orWhere('name', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();
        return api([
                'results' => $results
        ]);
    }

    public function sales_orders_sent()
    {
        $results = SalesOrder::with('currency')
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

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_salesorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'client_id' => 'sometimes|required|integer|exists:clients,id'
        ]);

        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $vatrate = VatRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $sales_order_field_1 = DB::table('settings')->where('key','=','sales_order_field_1')->value('value');
        $sales_order_field_2 = DB::table('settings')->where('key','=','sales_order_field_2')->value('value');
        $sales_order_field_3 = DB::table('settings')->where('key','=','sales_order_field_3')->value('value');
        $sales_order_field_4 = DB::table('settings')->where('key','=','sales_order_field_4')->value('value');
        $form =[
            'client_id' => null,
            'client' => null,
            'number' => counter()->next('sales_order'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'delivery_date' => null,
            'paymentcondition_id' => null,
            'deliverycondition_id' => null,
            'exchangerate' => $exchange,
            'vatrate' => $vatrate,
            'terms' => null,
            'discount' => 0,
            'shipping' => 0,
            'line1_text' => $sales_order_field_1,
            'line1_value' => 0,
            'line2_text' => $sales_order_field_2,
            'line2_value' => 0,
            'line3_text' => $sales_order_field_3,
            'line3_value' => 0,
            'line4_text' => $sales_order_field_4,
            'line4_value' => 0,
            'items' => [
                [
                    'product' => null,
                    'product_id' => null,
                    'price' => 0,
                    'quantity' => 1,
                    'uom'=> 0,
                    'taxes' => []
                ]
            ]
            ];
        if($request->has('user_id')) {
            $client = Client::with(['currency'])->findOrFail($request->user_id);
          
            array_set($form, 'user_id', $client->id);
            array_set($form, 'client', $client);
            array_set($form, 'currency_id', $client->currency->id);
            array_set($form, 'currency', $client->currency);

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
        if ($user->is_salesorders_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'required|integer',
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            // 'quotation_id' => 'sometimes|required|exists:quotations,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable',
            'delivery_date' => 'nullable',
            'paymentcondition_id' => 'required',
            'deliverycondition_id' => 'required',
            'discount' => 'nullable',
            'shipping' => 'nullable',
            'vat_status' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.location' => 'nullable',
            // 'items.*.uom_id' => 'nullable',
            // 'items.*.taxes.*.name' => 'required|max:255',
            // 'items.*.taxes.*.rate' => 'required|numeric|min:0',
            // 'items.*.taxes.*.tax_authority' => 'required|max:255',
            'terms' => 'nullable|max:2000',
            'document' => 'nullable|image|max:2048'
        ]);

        $model = new SalesOrder();
        $model->fill($request->except('items', 'document'));

        // $model->user_id = auth()->id();
        
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;

        $client_name_id = Client::where('id','=',$request->user_id)->value('name');
        $client_phone_id = Client::where('id','=',$request->user_id)->value('phone');
        
        

        $model->name = $client_name_id;
        $model->phone = $client_phone_id;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;

        $model->status_id = SalesOrder::DRAFT;
        $username = Auth::user()->name;
        $model ->created_by = $username;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        // $model->quotation_id = $request->get('quotation_id', null);
         
       

        $items = collect($request->items);
        $model->sub_total = $items->sum(function($item) {
            return $item['quantity'] * $item['price'];
        });


        

        // add tax
        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['price'] * $item['quantity']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);

        
        //discount
        $model->discount = $request->discount;
        //shipping
        $model->shipping = $request->shipping;
        // total tax
        if($request->vat_status == 0)
        {
            $total_seller_amount = $model->sub_total +  $model->shipping -  $model->discount ;
            $model->total_amount = $model->sub_total+  $model->shipping -  $model->discount ;
            $model->total = $model->sub_total+  $model->shipping -  $model->discount ;
        } else {
            $total_seller_amount = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            $model->total_amount = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
        }
        $seller_commission = Sellers::where('id','=',$request->seller_id)->value('commission');
        // $model->seller_commission = ($seller_commission/100) * $total_seller_amount;

        if($request->seller_commission > 0){
            $model->seller_commission = $request->seller_commission;
        }else{
            $model->seller_commission = ($seller_commission/100) * $total_seller_amount;
        }
        
        $model->status = 'pending';
        $model->payment_status = 'pending';
        $model->payment_gateway = 'cash_on_delivery';
    //    $model->total = $model->sub_total + $totalTax -  $model->discount +  $model->shipping;
        // $model->total = $model->sub_total  -  $model->discount +  $model->shipping;

        $model->client_id = $request->client_id;

        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('sales_order');

            $model->storeHasMany([
                'items.taxes' => $request->items
            ]);

            // update parent quotation to sales ordered
            if($model->quotation_id) {
               $quotation = $model->quotation;
               if(in_array($quotation->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                   $quotation->status_id = Quotation::SALES_ORDERED;
                   $quotation->save();
               }
           }

            counter()->increment('sales_order');

            return $model;
        });


        $order_log = new SalesOrderLog();
        $order_log->fill($request->except('items', 'document'));
        // $order_log->user_id = auth()->id();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $order_log->manager_id = $manager_id;
        $client_name_id = Client::where('id','=',$request->user_id)->value('name');
        $client_phone_id = Client::where('id','=',$request->user_id)->value('phone');
        $order_log->name = $client_name_id;
        $order_log->phone = $client_phone_id;
        $order_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        $order_log->status_id = SalesOrderLog::DRAFT;
        $username = Auth::user()->name;
        $order_log ->created_by = $username;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $order_log->document = $fileName;
           }
        }
        // $order_log->quotation_id = $request->get('quotation_id', null);
        $items = collect($request->items);
        $order_log->sub_total = $items->sum(function($item) {
            return $item['quantity'] * $item['price'];
        });
        // add tax
        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['price'] * $item['quantity']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);
        $order_log->discount = $request->discount;
        $order_log->shipping = $request->shipping;
        if($request->vat_status == 0)
        {
            $total_seller_amount = $order_log->sub_total +  $order_log->shipping -  $order_log->discount ;
            $order_log->total_amount = $order_log->sub_total+  $order_log->shipping -  $order_log->discount ;
            $order_log->total = $order_log->sub_total+  $order_log->shipping -  $order_log->discount ;
        } else {
            $total_seller_amount = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
            $order_log->total_amount = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
            $order_log->total = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
        }
        $seller_commission = Sellers::where('id','=',$request->seller_id)->value('commission');
        if($request->seller_commission > 0){
            $order_log->seller_commission = $request->seller_commission;
        }else{
            $order_log->seller_commission = ($seller_commission/100) * $total_seller_amount;
        }
        $order_log->status = 'pending';
        $order_log->payment_status = 'pending';
        $order_log->payment_gateway = 'cash_on_delivery';
        $order_log->client_id = $request->client_id;
        
        $order_log->body = \App\SalesOrder\SalesOrder::where('id','=',$model->id)->get();
        $order_log->items = \App\SalesOrder\Item::where('order_id','=',$model->id)->get();
        
        $order_log = DB::transaction(function() use ($order_log, $request) {
            $order_log->number = counter()->next('sales_order');
            $order_log->storeHasMany([
                'items' => $request->items
            ]);
            // update parent quotation to sales ordered
            if($order_log->quotation_id) {
               $quotation = $order_log->quotation;
               if(in_array($quotation->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                   $quotation->status_id = Quotation::SALES_ORDERED;
                   $quotation->save();
               }
           }
            return $order_log;
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
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            // , 'quotation'
        $data = SalesOrder::with(['items.product','items.uom', 'items.taxes', 'client','seller', 'currency','paymentcondition','deliverycondition'])
            ->findOrFail($id);
        return api([
            'data' => $data
        ]);
        }
    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_salesorders_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = SalesOrder::with(['items.product','items.taxes', 'items.uom', 'client','seller', 'currency','paymentcondition','deliverycondition'])
            ->findOrFail($id);

        $doc  = 'docs.sales_order';

        if($request->has('mode') && $request->mode == 'pick') {
            $doc = 'docs.pick';
        }

        if($request->has('mode') && $request->mode == 'picklocation') {
            $data = SalesOrder::with(['items_location.product','items.taxes', 'items_location.uom', 'client','seller', 'currency','paymentcondition','deliverycondition'])
            ->findOrFail($id);
            $doc = 'docs.pick_location';
        }
        

        return pdf($doc, $data);
        }
    }

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_salesorders_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = SalesOrder::with(['items.product', 'items.uom', 'items.taxes','items.uom_unit','seller', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('sales_order');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    unset($form->quotation_id);
                    unset($form->document);
                    break;

                case 'invoice':

                    $form->number = counter()->next('invoice');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    $form->parent_id = $form->id;
                    $form->discount_usd = 1;
                    $form->discount_percentage = 0;
                    $form->parent_type = 'sales_order';
                    unset($form->quotation_id);
                    unset($form->document);
                    break;
                case 'goods_issue':
                    $form->sales_order_id = $form->id;
                    $form->sales_order_number = $form->number;
                    $form->number = counter()->next('goods_issue');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    unset($form->quotation_id);
                    unset($form->document);
                    $items = $form->items->map(function($query) {
                        $query->qty = $query->qty - $query->qty_issued;
                        if($query->qty > 0) {
                            $query->qty_issued = 0;
                            return $query;
                        }
                    })->reject(function($item) {
                        return is_null($item);
                    });
                    unset($form->items);
                    $form->items = $items;
                    break;
                    break;

                default:
                    abort(404, 'Invalid Mode');
                    break;
            }
        } else {
            // abort if not editable
            abort_if(!$form->is_editable, 404);
        }

        unset($form->document);

        return api([
            'form' => $form
        ]);
        }
    }

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_salesorders_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = SalesOrder::findOrFail($id);


        // abort if not editable
        abort_if(!$model->is_editable, 404);

        $request->validate([
            'user_id' => 'nullable',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'due_date' => 'nullable',
            'delivery_date' => 'nullable',
            'paymentcondition_id' => 'nullable',
            'deliverycondition_id' => 'nullable',
            'discount' => 'nullable',
            'shipping' => 'nullable',
            'vat_status' => 'nullable',
            'items' => 'nullable|array|min:1',
            // 'items.*.id' => 'sometimes|integer|exists:sales_order_items,id,order_id,'.$model->id,
            'items.*.item_id' => 'nullable|integer',
            'items.*.price' => 'nullable|numeric|min:0',
            'items.*.quantity' => 'nullable|numeric|min:0',
            'items.*.location' => 'nullable',
            'items.*.uom_id' => 'nullable',
            'items.*.taxes.*.name' => 'nullable|max:255',
            'items.*.taxes.*.rate' => 'nullable|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'nullable|max:255',
            'terms' => 'nullable|max:2000',
            'document' => 'nullable|image|max:2048'
        ]);

        $model->fill($request->except('items'));

        // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                // overwrite previous uploaded file
                deleteFile($model->document);
                $model->document = $fileName;
           }
        }
       
        $items = collect($request->items);
        $model->sub_total = $items->sum(function($item) {
            return $item['quantity'] * $item['price'];
        });

         // add tax
        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['price'] * $item['quantity']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);
       
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        //discount
        $model->discount = $request->discount;
        //shipping
        $model->shipping = $request->shipping;
        // total tax
        if($request->vat_status == 0)
        {
            $total_seller_amount = $model->sub_total +  $model->shipping -  $model->discount ;
            $model->total_amount = $model->sub_total+  $model->shipping -  $model->discount ;
            $model->total = $model->sub_total+  $model->shipping -  $model->discount ;
        }else {
            $total_seller_amount = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            $model->total_amount = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
        }
        $seller_commission = Sellers::where('id','=',$request->seller_id)->value('commission');
        if($request->seller_commission > 0){
            $model->seller_commission = $request->seller_commission;
        }else{
            $model->seller_commission = ($seller_commission/100) * $total_seller_amount;
        }
        
        $model->status = 'pending';
        $model->payment_status = 'pending';
        $model->payment_gateway = 'cash_on_delivery';
        
        // $model->total_amount = $model->sub_total + $totalTax -  $model->discount +  $model->shipping;
       //  $model->total = $model->sub_total  -  $model->discount +  $model->shipping;
        $username = Auth::user()->name;
        $model ->created_by = $username;

        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items.taxes' => $request->items
            ]);

            return $model;
        });

        $order_log = new SalesOrderLog();
        $order_log->fill($request->except('items', 'document'));
        // $order_log->user_id = auth()->id();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $order_log->manager_id = $manager_id;
        $client_name_id = Client::where('id','=',$request->user_id)->value('name');
        $client_phone_id = Client::where('id','=',$request->user_id)->value('phone');
        $order_log->name = $client_name_id;
        $order_log->phone = $client_phone_id;
        $order_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        $order_log->status_id = SalesOrderLog::DRAFT;
        $username = Auth::user()->name;
        $order_log ->created_by = $username;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $order_log->document = $fileName;
           }
        }
        // $order_log->quotation_id = $request->get('quotation_id', null);
        $items = collect($request->items);
        $order_log->sub_total = $items->sum(function($item) {
            return $item['quantity'] * $item['price'];
        });
        // add tax
        $totalTax = $items->reduce(function($carry, $item) {
            if(isset($item['taxes']) && count($item['taxes']) > 0) {
                $taxes = collect($item['taxes'])->reduce(function($c, $tax)  use ($item) {
                    return $c + (($item['price'] * $item['quantity']) * $tax['rate'] / 100);
                }, 0);
                return $carry + $taxes;
            } else {
                return 0;
            }
        }, 0);
        $order_log->discount = $request->discount;
        $order_log->shipping = $request->shipping;
        if($request->vat_status == 0)
        {
            $total_seller_amount = $order_log->sub_total +  $order_log->shipping -  $order_log->discount ;
            $order_log->total_amount = $order_log->sub_total+  $order_log->shipping -  $order_log->discount ;
            $order_log->total = $order_log->sub_total+  $order_log->shipping -  $order_log->discount ;
        } else {
            $total_seller_amount = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
            $order_log->total_amount = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
            $order_log->total = $order_log->sub_total + $totalTax  +  $order_log->shipping -  $order_log->discount ;
        }
        $seller_commission = Sellers::where('id','=',$request->seller_id)->value('commission');
        if($request->seller_commission > 0){
            $order_log->seller_commission = $request->seller_commission;
        }else{
            $order_log->seller_commission = ($seller_commission/100) * $total_seller_amount;
        }
        $order_log->status = 'pending';
        $order_log->payment_status = 'pending';
        $order_log->payment_gateway = 'cash_on_delivery';
        $order_log->client_id = $request->client_id;
            $order_log->body = \App\SalesOrder\SalesOrder::where('id','=',$model->id)->get();
        $order_log->items = \App\SalesOrder\Item::where('order_id','=',$id)->get();
        $order_log = DB::transaction(function() use ($order_log, $request) {
            $order_log->number = counter()->next('sales_order');
            $order_log->storeHasMany([
                'items' => $request->items
            ]);
            // update parent quotation to sales ordered
            if($order_log->quotation_id) {
               $quotation = $order_log->quotation;
               if(in_array($quotation->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                   $quotation->status_id = Quotation::SALES_ORDERED;
                   $quotation->save();
               }
           }
            return $order_log;
        });


        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function markAs($id, Request $request)
    {
        $model = SalesOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:2,3,4,5,6,9'
        ]);

        switch ($request->status) {
            case (SalesOrder::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    SalesOrder::DRAFT
                ]), 404);

                $model->request_date = date('Y-m-d');
                DB::table('sales_orders')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $sales_email = Settings::where('key','=','sales_orders_email')->value('value');
                if($sales_email == 1){
                    $sales_order = SalesOrder::with(['items.product', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'sales_order', $sales_order
                    ));
                }

                $sales_number = SalesOrder::where('id','=',$id)->value('number');
                $sales_notification = Settings::where('key','=','sales_orders_notification')->value('value');
                if($sales_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $sales_number;
                    $notification->document_type = 'sales';
                    $notification->description = 'New Sales Order Created '.$sales_number;
                    $notification->link = 'sales_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'manager';
                    counter()->increment('notifications');
                    $notification->save();

                }

        
                // $seller_payment = new SellerPayment();
                // $seller_payment->number = counter()->next('seller_payments');
                // $seller_payment->user_id = $model->user_id;
                // $seller_payment->seller_id = $model->seller_id;
                // $seller_payment->client_id = $model->client_id;
                // $seller_payment->order_id = $id;
                // $seller_payment->total_amount = $model->seller_commission;
                // $seller_payment->order_amount = $model->total_amount;
                // $seller_payment->amount_received = 0;
                // $seller_payment->amount_pending = $model->seller_commission;
                // $seller_payment->status_id = 1;
                // $seller_payment->currency_id = 1;
                // $seller_payment->date = date('Y-m-d');
                // $seller_payment->year_date = date('Y');
                // $seller_payment->payment_at = null;
                // $seller_payment->payment_date = null;
                // $seller_payment->paid_by = null;
                // $seller_payment->payment_mode = null;
                // $seller_payment->payment_reference = null;
                // $seller_payment->document = null;
                // $seller_payment->note = null;
                // $seller_payment->save();
                // counter()->increment('seller_payments');
                
                // $model->status_id = SalesOrder::SENT;

                // $seller_balance = Sellers::where('id','=',$model->seller_id)->value('commission_balance');
                // Sellers::where('id','=',$model->seller_id)->update(['commission_balance' => $seller_balance + $model->seller_commission]);
                    
                // $seller_wallets = SellerWallet::where('seller_id','=', $model->seller_id)->value('total_earning');
                // SellerWallet::where('seller_id','=', $model->seller_id)->update(['total_earning' => $seller_wallets + $model->seller_commission]);
                $model->status_id = SalesOrder::SENT;

                break;

            case (SalesOrder::CONFIRMED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    SalesOrder::DRAFT, SalesOrder::SENT,
                    SalesOrder::HOLD
                ]), 404);

                $model->confirmed_date = date('Y-m-d');
                DB::table('sales_orders')
                    ->where('id', $id)
                    ->update(['confirmed_date' => date('Y-m-d')]);

                $sales_email = Settings::where('key','=','sales_orders_email')->value('value');
                if($sales_email == 1){
                    $sales_order = SalesOrder::with(['items.product', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Confirmed(
                        $request->only('to', 'bcc','subject', 'message'),
                        'sales_order', $sales_order
                    ));
                }

                $sales_order_items = SalesOrderItem::where('order_id','=',$id)->get();
                foreach($sales_order_items as $itemS){
                    $on_hold_qty = \App\Product\Product::where('code','=', $itemS->item_id)->value('on_hold_qty');
                    $base_uom_id = \App\Product\Product::where('code','=', $itemS->item_id)->value('uom_id');
                    if($itemS->uom_id != $base_uom_id){
                    // throw new \Exception($itemS->uom_id);
                    $conversion_factor = \App\Product\Conversion::where('product_id','=',$itemS->item_id)
                    ->where('converted_uom_id','=',$itemS->uom_id)->value('converted_qty');

                    $converted_qty = round($itemS->quantity / $conversion_factor);
                    \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty + $converted_qty]);
                    }else{
                        \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty + $itemS->quantity]);
                    }
                    
                }
                
                $sales_number = SalesOrder::where('id','=',$id)->value('number');
                $sales_user = SalesOrder::where('id','=',$id)->value('user_id');
                $sales_notification = Settings::where('key','=','sales_orders_notification')->value('value');
                if($sales_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $sales_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $sales_number;
                    $notification->document_type = 'sales';
                    $notification->description = 'Sales Order '.$sales_number.' Approved';
                    $notification->link = 'sales_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                // $seller_payment = new SellerPayment();
                // $seller_payment->number = counter()->next('seller_payments');
                // $seller_payment->user_id = $model->user_id;
                // $seller_payment->seller_id = $model->seller_id;
                // $seller_payment->client_id = $model->client_id;
                // $seller_payment->order_id = $id;
                // $seller_payment->total_amount = $model->seller_commission;
                // $seller_payment->order_amount = $model->total_amount;
                // $seller_payment->amount_received = 0;
                // $seller_payment->amount_pending = $model->seller_commission;
                // $seller_payment->status_id = 2;
                // $seller_payment->currency_id = 1;
                // $seller_payment->date = date('Y-m-d');
                // $seller_payment->year_date = date('Y');
                // $seller_payment->payment_at = null;
                // $seller_payment->payment_date = null;
                // $seller_payment->paid_by = null;
                // $seller_payment->payment_mode = null;
                // $seller_payment->payment_reference = null;
                // $seller_payment->document = null;
                // $seller_payment->note = null;
                // $seller_payment->save();
                // counter()->increment('seller_payments');
                
              

                // $seller_balance = Sellers::where('id','=',$model->seller_id)->value('commission_balance');
                // Sellers::where('id','=',$model->seller_id)->update(['commission_balance' => $seller_balance + $model->seller_commission]);
                    
            

                // $seller_payment = new SellerPayment();
                // $seller_payment->number = counter()->next('seller_payments');
                // $seller_payment->user_id = $model->user_id;
                // $seller_payment->seller_id = $model->seller_id;
                // $seller_payment->order_id = $id;
                // $seller_payment->total_amount = $model->seller_commission;
                // $seller_payment->order_amount = $model->total_amount;
                // $seller_payment->amount_received = 0;
                // $seller_payment->amount_pending = $model->seller_commission;
                // $seller_payment->status_id = 1;
                // $seller_payment->currency_id = 1;
                // $seller_payment->date = date('Y-m-d');
                // $seller_payment->year_date = date('Y');
                // $seller_payment->payment_at = null;
                // $seller_payment->payment_date = null;
                // $seller_payment->paid_by = null;
                // $seller_payment->payment_mode = null;
                // $seller_payment->payment_reference = null;
                // $seller_payment->document = null;
                // $seller_payment->note = null;
                // $seller_payment->save();
                // counter()->increment('seller_payments');

                if($model->posted != 1){
                    $documentData = $model->toArray();
                    $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'sales_order');
                    if (!$journalVoucher) {
                        throw new \Exception ("SalesOrder saved but failed to create journal entries");
                    }else{
                        $model->posted = 0;
                        $journal_id = \App\SalesOrder\SalesOrder::where('id','=',$model->id)->value('journal_id');
                        $model->journal_id = $journal_id;
                    }
                }

                $model->status_id = SalesOrder::CONFIRMED;
                break;

            case (SalesOrder::HOLD) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    SalesOrder::DRAFT, SalesOrder::SENT,
                    SalesOrder::CONFIRMED
                ]), 404);

                $model->status_id = SalesOrder::HOLD;
                break;

            case (SalesOrder::CLOSED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    SalesOrder::CONFIRMED
                ]), 404);

                $model->status_id = SalesOrder::CLOSED;
                break;

            case (SalesOrder::REOPEN) :
                    // must be draft
                    if($model->posted == 1){
                        throw new \Exception ("Delete JV First");
                    }

                    $invoice_status = \App\Invoice\Invoice::where('invoiceable_id','=',$model->id)->where('invoiceable_type','=','App\SalesOrder\SalesOrder')->value('status_id');
                    if($invoice_status > 0){
                        return response()->json(['error' => 'Forbidden.'], 403);
                    }else{
                        
                        if($model->status_id > 2){
                           $sales_order_items = SalesOrderItem::where('order_id','=',$id)->get();
                        foreach($sales_order_items as $itemS){
                            $on_hold_qty = \App\Product\Product::where('code','=', $itemS->item_id)->value('on_hold_qty');
                            $base_uom_id = \App\Product\Product::where('code','=', $itemS->item_id)->value('uom_id');
                            if($itemS->uom_id != $base_uom_id){
                            // throw new \Exception($itemS->uom_id);
                            $conversion_factor = \App\Product\Conversion::where('product_id','=',$itemS->item_id)
                            ->where('converted_uom_id','=',$itemS->uom_id)->value('converted_qty');

                            $converted_qty = round($itemS->quantity / $conversion_factor);
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty - $conversion_factor]);
                            }else{
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty - $itemS->quantity]);
                            }

                            
                            
                        } 
                        }
                        
                        
                        $model->status_id = SalesOrder::DRAFT;
                      
                    }
                    
                break;
    
            case (SalesOrder::VOID) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    SalesOrder::DRAFT, SalesOrder::SENT,
                    SalesOrder::CONFIRMED,  SalesOrder::CLOSED,
                    SalesOrder::HOLD
                ]), 404);

                $model->declined_date = date('Y-m-d');
                DB::table('sales_orders')
                    ->where('id', $id)
                    ->update(['declined_date' => date('Y-m-d')]);

                $sales_email = Settings::where('key','=','sales_orders_email')->value('value');
                if($sales_email == 1){
                    $sales_order = SalesOrder::with(['items.product', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Declined(
                        $request->only('to', 'bcc','subject', 'message'),
                        'sales_order', $sales_order
                    ));
                }

                $sales_number = SalesOrder::where('id','=',$id)->value('number');
                $sales_user = SalesOrder::where('id','=',$id)->value('user_id');
                $sales_notification = Settings::where('key','=','sales_orders_notification')->value('value');
                if($sales_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $sales_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $sales_number;
                    $notification->document_type = 'sales';
                    $notification->description = 'Sales Order '.$sales_number.'  Declined';
                    $notification->link = 'sales_orders/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = SalesOrder::VOID;
                break;

            default:
                abort(404, 'Invalid Operation');
                break;
        }

        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id,
            'status_id' => $model->status_id,
            'posted' => $model->posted,
            'journal_id' => $model->journal_id,
            'is_editable' => $model->is_editable
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_salesorders_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = SalesOrder::findOrFail($id);

        $invoice_log = new SalesOrderLog();
        $invoice_log->number = $model->number;
        $invoice_log->user_id = $model->user_id;
        $invoice_log->client_id = $model->client_id;
        $invoice_log->date = $model->date;
        $invoice_log->sub_total = $model->sub_total;
        $invoice_log->total = $model->total;
        $invoice_log->currency_id = 1;
        $invoice_log->name = 'name';
        $invoice_log->comment = 'Order_Deleted';
    
            $invoice_log->body = \App\SalesOrder\SalesOrder::where('id','=',$model->id)->get();
        $invoice_log->items = \App\SalesOrder\Item::where('order_id','=',$model->id)->get();
            $invoice_log->save();
        $invouice_item_log = new \App\SalesOrder\ItemLog;
        $invouice_item_log->item_id = 1;
        $invouice_item_log->order_id = $model->id;
        $invouice_item_log->body = \App\SalesOrder\Item::where('order_id','=',$model->id)->get();
        $invouice_item_log->save();

        // check whether this particular sales order belongs to
        $invoices = $model->invoices()->count();

        // invoice, etc.
        // if yes provide warning

        if($invoices) {
            return api([
                'message' => 'Delete all the sales order relations first',
                'errors' => []
            ], 422);
        }

        $model->items()->delete();

        // delete uploaded file
        deleteFile($model->document);

        $model->delete();

        return api([
            'deleted' => true
        ]);
    }}


    public function filter(Request $request)
    {
        $client_name      = $request->client_name;
        $from_date        = $request->from_date;
        $to_date          = $request->to_date;
        $amount           = $request->amount;
        $client_dropdown1 = $request->client_dropdown1;
        $client_dropdown2 = $request->client_dropdown2;
        $client_seller    = $request->client_seller;
    
        // Start building the query
        $query = SalesOrder::with(['client', 'currency'])->orderby('created_at','desc');
    
        // Filter by client_dropdown1
        if (!empty($client_dropdown1)) {
            $client_dropdown1_id = \App\ClientDropDown1::where('name', '=', $client_dropdown1)->value('id');
            if ($client_dropdown1_id) {
                $clients1 = \App\Client::where('client_dropdown_1_id', $client_dropdown1_id)->pluck('id');
                $query->whereIn('client_id', $clients1);
            }
        }
    
        // Filter by client_dropdown2
        if (!empty($client_dropdown2)) {
            $client_dropdown2_id = \App\ClientDropDown2::where('name', '=', $client_dropdown2)->value('id');
            if ($client_dropdown2_id) {
                $clients2 = \App\Client::where('client_dropdown_2_id', $client_dropdown2_id)->pluck('id');
                $query->whereIn('client_id', $clients2);
            }
        }
    
        // Filter by client seller
        if (!empty($client_seller)) {
            $client_seller_id = \App\Sellers\Sellers::where('name', '=', $client_seller)->value('id');
            if ($client_seller_id) {
                $clientsSeller = \App\Client::where('seller_id', $client_seller_id)->pluck('id');
                $query->whereIn('client_id', $clientsSeller);
            }
        }
    
        // Filter by client name
        if (!empty($client_name)  && $client_name != 0) {
            $capitalize = (strtolower($client_name));
            $clientsName = \App\Client::where(DB::raw('LOWER(name)'), 'LIKE', '%' . $capitalize . '%')->pluck('id');
            // throw new Exception($clientsName);
            $query->whereIn('client_id', $clientsName);
        }
    
       

        // Filter by date range
        if (!empty($from_date) && $from_date != 0) {
            $query->where('date', '>=',$from_date);
        }

          // Filter by date range
          if ( !empty($to_date) && $to_date != 0 ) {
            $query->where('date', '<=',$to_date);
        }
    
        // Filter by amount
        if (!empty($amount)) {
            $query->where('total', '>=', $amount);
        }
    
        // Return the result
        return api([
            'data' => $query->search()
        ]);
    }
    
    
}
