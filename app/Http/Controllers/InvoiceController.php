<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice\Invoice;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;
use App\Client;
use DB;
use Auth;
use App\PaymentCondition;
use App\ExchangeRate\ExchangeRate;
use App\Invoice\Item;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\VatRate\VatRate;
use App\FinishedProduct\FinishedProduct;



use Carbon\Carbon;
use App\Invoice\InvoiceLog;
use App\StockMovement\StockMovement;
use App\Mail\Invoices\Send;
use App\Mail\Invoices\Confirmed;
use App\Mail\Invoices\Declined;
use App\Settings;
use Mail;
use App\User;
use App\Notifications;
use App\CreditNote\CreditNote;
use App\CreditNote\Item as CreditNoteItem;

use App\SellerPayment\SellerPayment;
use App\Sellers\Sellers;
use App\Services\JournalService;

class InvoiceController extends Controller
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
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Invoice::with('client', 'currency')->orderby('created_at','desc')->search()
            ]);
        }
    }

    public function search()
    {
        $results = Invoice::with('client','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get();
            return api([
                'results' => $results
            ]);
    }

    public function invoices_sent()
    {
        $results = Invoice::with('client','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','=',1)
            ->limit(15)
            ->get();
            
            return api([
                'results' => $results
            ]);
    }

    public function invoices_confirmed()
    {
        $results = Invoice::with('client','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','>',1)
            ->limit(15)
            ->get();
            
            return api([
                'results' => $results
            ]);
    }

    
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_invoices_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'sometimes|required|integer|exists:clients,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $vatrate = VatRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $invoice_field_1 = DB::table('settings')->where('key','=','invoice_field_1')->value('value');
        $invoice_field_2 = DB::table('settings')->where('key','=','invoice_field_2')->value('value');
        $invoice_field_3 = DB::table('settings')->where('key','=','invoice_field_3')->value('value');
        $invoice_field_4 = DB::table('settings')->where('key','=','invoice_field_4')->value('value');
        $form = [
            'user_id' => null,
            'client' => null,
            'number' => counter()->next('invoice'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'delivery_date' => null,
            'paymentcondition_id' => null,
            'deliverycondition_id' => null,
            'debit_amount' => null,
            'credit_amount' => null,
            'qty_on_hand' => 0,
            'exchangerate' => $exchange,
            'vatrate' => $vatrate,
            'terms' => null,
            'discount' => 0,
            'discount_percentage' => 0,
            'discount_usd' => 1,
            'shipping' => 0,
            'line1_text' => $invoice_field_1,
            'line1_value' => 0,
            'line2_text' => $invoice_field_2,
            'line2_value' => 0,
            'line3_text' => $invoice_field_3,
            'line3_value' => 0,
            'line4_text' => $invoice_field_4,
            'line4_value' => 0,
            'items' => [
                [
                    'product' => null,
                    'product_id' => null,
                    'unit_price' => 0,
                    'price' => 0,
                    'uom'=> 0,
                    'uom'=> 1,
                    'qty' => 1,
                    'quantity' => 1,
                    'discount_usd'=> 0,
                    'discount_per'=> 0,
                    'tax' => []
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
        if ($user->is_invoices_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable',
            'delivery_date' => 'nullable',
            'paymentcondition_id' => 'required',
            'deliverycondition_id' => 'required',
            'discount' => 'nullable',
            'shipping' => 'nullable',
            'debit_amount' => 'nullable',
            'credit_amount' => 'nullable',
            'vat_status' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|integer',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.discount_usd' => 'required|numeric|min:0',
            'items.*.qty_on_hand' => 'nullable',
            'items.*.uom_unit' => 'nullable',
            'items.*.uom_id' => 'nullable',
            // 'items.*.taxes.*.name' => 'required|max:255',
            // 'items.*.taxes.*.rate' => 'required|numeric|min:0',
            // 'items.*.taxes.*.tax_authority' => 'required|max:255',
            'terms' => 'nullable|max:2000',
            // 'parent_type' => 'sometimes|required|alpha_dash|in:quotation,sales_order',
            // 'parent_id' => 'required_with:parent_type|integer|exists:'.$request->parent_type.'s,id'
        ]);

        $model = new Invoice();
        $model->fill($request->except('items'));

        // $model->user_id = auth()->id();
        $model->status_id = Invoice::DRAFT;
        $model->amount_paid = 0;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        
        // detemine the parent, if parent_id and parent_type present
        if($request->has('parent_type')) {
            switch ($request->parent_type) {
                case 'quotation':
                    $model->invoiceable_type = 'App\Quotation\Quotation';
                    $model->invoiceable_id = $request->parent_id;
                    break;

                case 'sales_order':
                    $model->invoiceable_type = 'App\SalesOrder\SalesOrder';
                    $model->invoiceable_id = $request->parent_id;
                    break;

                default:
                    abort(404, 'Invalid Operation');
                    break;
            }
        }

        $items = collect($request->items);
        
        foreach($items as $item1){
            $invoices_available_qty = DB::table('settings')->where('id','=',26)->value('value');
            $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($invoices_available_qty == 1){
                if( ($item1['quantity'] > $item1['qty_on_hand']) )
                {
                    return response()->json(['error' => 'Forbidden.'], 411);
                }

                //Stock movment if available qty
                // $get_price = Product::where('id','=',$id)->value('unit_price');
                $get_uom = Product::where('id','=',$item1['item_id'])->value('unit');
                // $get_currency = Product::where('id','=',$id)->value('currency_id');
                $get_vendor = ProductItem::where('product_id','=',$item1['item_id'])->value('vendor_id');
                $get_category = Product::where('id','=',$item1['item_id'])->value('category_id');
                $get_sub_category = Product::where('id','=',$item1['item_id'])->value('sub_category_id');
                $get_warehouse = Product::where('id','=',$item1['item_id'])->value('warehouse_id');
                $get_name = Product::where('id','=',$item1['item_id'])->value('description');
                $get_code = Product::where('id','=',$item1['item_id'])->value('code');
                $new_code = counter()->next('invoice');

                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $item1['item_id'];
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->warehouse_id = $get_warehouse;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $item1['quantity'];
                $stock_movement->uom = $get_uom;
                $stock_movement->price = $item1['price'];
                $stock_movement->currency = $request->currency_id;
                $stock_movement->purchase_order = $new_code;
                $stock_movement->type = "Invoiced Draft";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

            }else{
                 //Stock movment if available qty or no
                 $get_uom = Product::where('id','=',$item1['item_id'])->value('unit');
                 // $get_currency = Product::where('id','=',$id)->value('currency_id');
                 $get_vendor = ProductItem::where('product_id','=',$item1['item_id'])->value('vendor_id');
                 $get_category = Product::where('id','=',$item1['item_id'])->value('category_id');
                 $get_sub_category = Product::where('id','=',$item1['item_id'])->value('sub_category_id');
                 $get_warehouse = Product::where('id','=',$item1['item_id'])->value('warehouse_id');
                 $get_name = Product::where('id','=',$item1['item_id'])->value('description');
                 $get_code = Product::where('id','=',$item1['item_id'])->value('code');
                 $new_code = counter()->next('invoice');

                 $stock_movement = new StockMovement();
                 $stock_movement->user_id = auth()->id();
                 $stock_movement->product_id = $item1['item_id'];
                 $stock_movement->product_code = $get_code;
                 $stock_movement->product_name = $get_name;
                 $stock_movement->category_id = $get_category;
                 $stock_movement->sub_category_id = $get_sub_category;
                 $stock_movement->warehouse_id = $get_warehouse;
                 $stock_movement->vendor_id = $get_vendor;
                 $stock_movement->qty =  $item1['quantity'];
                 $stock_movement->uom = $get_uom;
                 $stock_movement->price = $item1['price'];
                 $stock_movement->currency = $request->currency_id;
                 $stock_movement->purchase_order = $new_code;
                 $stock_movement->type = "Invoiced Draft";
                 $stock_movement->created_by = Auth::user()->name;
                 $stock_movement->save();
            }
        }

        if($request->discount_usd == 1 && $request->discount_per != 1){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_usd'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - ($item['quantity'] * $item['discount_usd']) );
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }

        if($request->discount_per == 1 && $request->discount_usd != 1){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount_per'] / 100)) );
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }


        if($request->discount_per != 1 && $request->discount_usd != 1 ){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity'])) );
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }
            

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
            $model->discount_percentage = $request->discount_percentage;
            //shipping
            $model->shipping = $request->shipping;
            // total tax
            if($request->vat_status == 0)
            {
                $model->total = $model->sub_total+  $model->shipping -  $model->discount - ($model->sub_total * ($request->discount_percentage / 100)) ;
            } else {
                $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount - ($model->sub_total * ($request->discount_percentage / 100)) ;
            }
                // $model->total = $model->sub_total + $totalTax +  $model->shipping -  $model->discount;
            // $model->total = $model->sub_total  -  $model->discount +  $model->shipping;
            
                $seller_commission_percentage = \App\Sellers\Sellers::where('id','=',$model->seller_id)->value('commission');
    
    $new_seller_commission = $model->total * ($seller_commission_percentage / 100);
    
    $model->seller_commission = $new_seller_commission;
            
            $username = Auth::user()->name;
            $model ->created_by = $username;

            $manager_id = User::where('id','=',auth()->id())->value('manager_id');
            $model->manager_id = $manager_id;

            $model->client_id = $request->user_id;

            $model = DB::transaction(function() use ($model, $request) {

                $model->number = counter()->next('invoice');

                $model->storeHasMany([
                    'items.taxes' => $request->items
                ]);

            //  update parent quotation to sales ordered
                if($model->invoiceable) {
                $invoiceable = $model->invoiceable;
                if($model->invoiceable_type == 'App\Quotation\Quotation') {
                        if(in_array($invoiceable->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                            $invoiceable->status_id = Quotation::INVOICED;
                            $invoiceable->save();
                        }
                } else if($model->invoiceable_type == 'App\SalesOrder\SalesOrder') {
                        if(in_array($invoiceable->status_id, [SalesOrder::SENT, SalesOrder::CONFIRMED, SalesOrder::ISSUED])) {
                            $invoiceable->status_id = SalesOrder::CLOSED;
                            $invoiceable->save();
                        }
                }
            }

                counter()->increment('invoice');

                return $model;
            });

                $id = $model->id;
                $invoices_number = Invoice::where('id','=',$id)->value('number');
                $invoices_user = Invoice::where('id','=',$id)->value('user_id');
                $invoices_notification = Settings::where('key','=','invoices_notification')->value('value');
                if($invoices_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    // $notification->manager_id = $invoices_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $invoices_number;
                    $notification->document_type = 'invoices';
                    $notification->description = 'New Invoice Created '.$invoices_number.'';
                    $notification->link = 'invoices/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }




        $invoice_log = new InvoiceLog();
        $invoice_log->fill($request->except('items'));

        // $invoice_log->user_id = auth()->id();
        $invoice_log->status_id = InvoiceLog::DRAFT;
        $invoice_log->amount_paid = 0;

        $invoice_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        
        // detemine the parent, if parent_id and parent_type present
        if($request->has('parent_type')) {
            switch ($request->parent_type) {
                case 'quotation':
                    $invoice_log->invoiceable_type = 'App\Quotation\Quotation';
                    $invoice_log->invoiceable_id = $request->parent_id;
                    break;

                case 'sales_order':
                    $invoice_log->invoiceable_type = 'App\SalesOrder\SalesOrder';
                    $invoice_log->invoiceable_id = $request->parent_id;
                    break;

                default:
                    abort(404, 'Invalid Operation');
                    break;
            }
        }

         $items = collect($request->items);
        
            if($request->discount_usd == 1 && $request->discount_per != 1){
                $items = collect($request->items);
                $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                    
                            $sub_totals =  collect($item['discount_usd'])->reduce(function($c, $tax)  use ($item) {
                            return $c + (($item['price'] * $item['quantity']) - $item['discount_usd']);
                        }, 0);

                        return $carry + $sub_totals;
                    },0);
            }

                if($request->discount_per == 1 && $request->discount_usd != 1){
                    $items = collect($request->items);
                    $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                        
                                $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                                return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount_per'] / 100)) );
                            }, 0);

                            return $carry + $sub_totals;
                        },0);
                }


            if($request->discount_per != 1 && $request->discount_usd != 1 ){
                $items = collect($request->items);
                $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                    
                            $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                            return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity'])) );
                        }, 0);

                        return $carry + $sub_totals;
                    },0);
            }
            

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
            $invoice_log->discount = $request->discount;
            $invoice_log->discount_percentage = $request->discount_percentage;
            //shipping
            $invoice_log->shipping = $request->shipping;
            // total tax
            if($request->vat_status == 0)
            {
                $invoice_log->total = $invoice_log->sub_total+  $invoice_log->shipping -  $invoice_log->discount - ($invoice_log->sub_total * ($request->discount_percentage / 100)) ;
            } else {
                $invoice_log->total = $invoice_log->sub_total + $totalTax  +  $invoice_log->shipping -  $invoice_log->discount - ($invoice_log->sub_total * ($request->discount_percentage / 100)) ;
            }
                // $invoice_log->total = $invoice_log->sub_total + $totalTax +  $invoice_log->shipping -  $invoice_log->discount;
            // $invoice_log->total = $invoice_log->sub_total  -  $invoice_log->discount +  $invoice_log->shipping;
            
            $username = Auth::user()->name;
            $invoice_log ->created_by = $username;

            $manager_id = User::where('id','=',auth()->id())->value('manager_id');
            $invoice_log->manager_id = $manager_id;

            $invoice_log->client_id = $request->user_id;

    $invoice_log->body = \App\Invoice\Invoice::where('id','=',$model->id)->get();
        $invoice_log->items = \App\Invoice\Item::where('invoice_id','=',$model->id)->get();
       
            
            $invoice_log = DB::transaction(function() use ($invoice_log, $request) {

                $invoice_log->number = counter()->next('invoice');

                $invoice_log->storeHasMany([
                    'items' => $request->items
                ]);

            //  update parent quotation to sales ordered
                if($invoice_log->invoiceable) {
                $invoiceable = $invoice_log->invoiceable;
                if($invoice_log->invoiceable_type == 'App\Quotation\Quotation') {
                        if(in_array($invoiceable->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                            $invoiceable->status_id = Quotation::INVOICED;
                            $invoiceable->save();
                        }
                } else if($invoice_log->invoiceable_type == 'App\SalesOrder\SalesOrder') {
                        if(in_array($invoiceable->status_id, [SalesOrder::SENT, SalesOrder::CONFIRMED, SalesOrder::ISSUED])) {
                            $invoiceable->status_id = SalesOrder::CLOSED;
                            $invoiceable->save();
                        }
                }
            }
                return $invoice_log;
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
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data =  Invoice::with([
            'items.product','items.uom', 'items.taxes', 'client', 'currency', 'invoiceable',
            'clientPayments.parent.currency','seller',
            'advancePayments.parent.currency','paymentcondition','deliverycondition'
        ])
            ->findOrFail($id);
        return api([
            'data' => $data
        ]);
            }


    }

    public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_invoices_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = Invoice::with(['items.product', 'seller','items.taxes', 'client', 'currency', 'invoiceable','paymentcondition','deliverycondition'])->findOrFail($id);
        $doc  = 'docs.invoice';

        if($request->has('mode') && $request->mode == 'delivery') {
            $doc = 'docs.delivery';
        }

        return pdf($doc, $data);
    }}

    public function edit($id, Request $request)
    {

        $user = auth()->user();
        if ($user->is_invoices_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = Invoice::with(['items.uom','items.product','seller', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('invoice');
                    $form->date = date('Y-m-d');
                    $form->due_date = null;
                    $form->reference = null;
                    $form->qty_on_hand = 0;
                    unset($form->invoiceable_id);
                    unset($form->invoiceable_type);
                    unset($form->amount_paid);
                    break;
                case 'customer_returns':
                        // i
                        $form->invoice_id = $form->id;
                        $form->invoice_number = $form->number;
                        $form->number = counter()->next('customer_returns');
                        $form->date = date('Y-m-d');
                        $form->note = null;
                        $items = $form->items->map(function($query) {
                            $query->qty = $query->qty - $query->qty_received;
                            $query->qty_returned =0;
                                $query->qty_received = $query->qty;
                                return $query;
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
        ]);}

    }

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_invoices_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Invoice::findOrFail($id);

        // abort if not editable
        abort_if(!$model->is_editable, 404);

        $request->validate([
            'user_id' => 'nullable|integer',
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
            'items.*.id' => 'sometimes|integer|exists:invoice_items,id,invoice_id,'.$model->id,
            'items.*.product_id' => 'nullable|integer',
            'items.*.unit_price' => 'nullable',
            'items.*.qty' => 'nullable|numeric|min:0',
            'items.*.qty_on_hand' => 'nullable',
            'items.*.uom_id' => 'nullable',
            'items.*.uom_unit' => 'nullable',
            'items.*.discount_usd' => 'required|numeric|min:0',
            'items.*.taxes.*.name' => 'nullable|max:255',
            'items.*.taxes.*.rate' => 'nullable|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'nullable|max:255',
            'terms' => 'nullable|max:2000',
            'debit_amount' => 'nullable',
            'credit_amount' => 'nullable'
        ]);

        $model->fill($request->except('items'));

        $items = collect($request->items);

        foreach($items as $item1){
            $invoices_available_qty = DB::table('settings')->where('id','=',26)->value('value');
            $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($invoices_available_qty == 1){
                if( ($item1['qty'] > $item1['qty_on_hand']) )
                {
                    return response()->json(['error' => 'Forbidden.'], 411);
                }
            }
               $get_uom = Product::where('id','=',$item1['item_id'])->value('unit');
                 // $get_currency = Product::where('id','=',$id)->value('currency_id');
                 $get_vendor = ProductItem::where('product_id','=',$item1['item_id'])->value('vendor_id');
                 $get_category = Product::where('id','=',$item1['item_id'])->value('category_id');
                 $get_sub_category = Product::where('id','=',$item1['item_id'])->value('sub_category_id');
                 $get_warehouse = Product::where('id','=',$item1['item_id'])->value('warehouse_id');
                 $get_name = Product::where('id','=',$item1['item_id'])->value('description');
                 $get_code = Product::where('id','=',$item1['item_id'])->value('code');
                 $new_code = Invoice::where('id','=',$id)->value('number');

                 $stock_movement = new StockMovement();
                 $stock_movement->user_id = auth()->id();
                 $stock_movement->product_id = $item1['item_id'];
                 $stock_movement->product_code = $get_code;
                 $stock_movement->product_name = $get_name;
                 $stock_movement->category_id = $get_category;
                 $stock_movement->sub_category_id = $get_sub_category;
                 $stock_movement->warehouse_id = $get_warehouse;
                 $stock_movement->vendor_id = $get_vendor;
                 $stock_movement->qty =  $item1['quantity'];
                 $stock_movement->uom = $get_uom;
                 $stock_movement->price = $item1['price'];
                 $stock_movement->currency = $request->currency_id;
                 $stock_movement->purchase_order = $new_code;
                 $stock_movement->type = "Invoiced Updated";
                 $stock_movement->created_by = Auth::user()->name;
                 $stock_movement->save();
        }

        // $model->sub_total = $items->sum(function($item) {
        //     return $item['qty'] * $item['unit_price'];
        // });

        if($request->discount_usd == 1 && $request->discount_per != 1){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_usd'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - ($item['quantity'] * $item['discount_usd']));
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }

        if($request->discount_per == 1 && $request->discount_usd != 1){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount_per'] / 100)) );
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }

        if($request->discount_per != 1 && $request->discount_usd != 1 ){
            $items = collect($request->items);
            $model->sub_total = $items->reduce(function($carry, $item) {
                
                        $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                        return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity'])) );
                    }, 0);

                    return $carry + $sub_totals;
                },0);
        }

        $username = Auth::user()->name;
        $model ->created_by = $username;

        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;
        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
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
          $model->discount_percentage = $request->discount_percentage;
          //shipping
          $model->shipping = $request->shipping;
          // total tax
          if($request->vat_status == 0)
          {
              $model->total = $model->sub_total+  $model->shipping -  $model->discount - ($model->sub_total * ($request->discount_percentage / 100)) ;
          } else {
              $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount - ($model->sub_total * ($request->discount_percentage / 100)) ;
          }
        //   $model->total = $model->sub_total + $totalTax +  $model->shipping -  $model->discount;
         // $model->total = $model->sub_total  -  $model->discount +  $model->shipping;

    $seller_commission_percentage = \App\Sellers\Sellers::where('id','=',$model->seller_id)->value('commission');
    
    $new_seller_commission = $model->total * ($seller_commission_percentage / 100);
    
    $model->seller_commission = $new_seller_commission;
    
        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items.taxes' => $request->items
            ]);

            return $model;
        });

        $invoice_log = new InvoiceLog();
        $invoice_log->fill($request->except('items'));

        // $invoice_log->user_id = auth()->id();
        $invoice_log->status_id = InvoiceLog::DRAFT;
        $invoice_log->amount_paid = 0;

        $invoice_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        
        // detemine the parent, if parent_id and parent_type present
        if($request->has('parent_type')) {
            switch ($request->parent_type) {
                case 'quotation':
                    $invoice_log->invoiceable_type = 'App\Quotation\Quotation';
                    $invoice_log->invoiceable_id = $request->parent_id;
                    break;

                case 'sales_order':
                    $invoice_log->invoiceable_type = 'App\SalesOrder\SalesOrder';
                    $invoice_log->invoiceable_id = $request->parent_id;
                    break;

                default:
                    abort(404, 'Invalid Operation');
                    break;
            }
        }

         $items = collect($request->items);
        
            if($request->discount_usd == 1 && $request->discount_per != 1){
                $items = collect($request->items);
                $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                    
                            $sub_totals =  collect($item['discount_usd'])->reduce(function($c, $tax)  use ($item) {
                            return $c + (($item['price'] * $item['quantity']) - $item['discount_usd']);
                        }, 0);

                        return $carry + $sub_totals;
                    },0);
            }

                if($request->discount_per == 1 && $request->discount_usd != 1){
                    $items = collect($request->items);
                    $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                        
                                $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                                return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity']) * ($item['discount_per'] / 100)) );
                            }, 0);

                            return $carry + $sub_totals;
                        },0);
                }


            if($request->discount_per != 1 && $request->discount_usd != 1 ){
                $items = collect($request->items);
                $invoice_log->sub_total = $items->reduce(function($carry, $item) {
                    
                            $sub_totals =  collect($item['discount_per'])->reduce(function($c, $tax)  use ($item) {
                            return $c + (($item['price'] * $item['quantity']) - (($item['price'] * $item['quantity'])) );
                        }, 0);

                        return $carry + $sub_totals;
                    },0);
            }
            

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
            $invoice_log->discount = $request->discount;
            $invoice_log->discount_percentage = $request->discount_percentage;
            //shipping
            $invoice_log->shipping = $request->shipping;
            // total tax
            if($request->vat_status == 0)
            {
                $invoice_log->total = $invoice_log->sub_total+  $invoice_log->shipping -  $invoice_log->discount - ($invoice_log->sub_total * ($request->discount_percentage / 100)) ;
            } else {
                $invoice_log->total = $invoice_log->sub_total + $totalTax  +  $invoice_log->shipping -  $invoice_log->discount - ($invoice_log->sub_total * ($request->discount_percentage / 100)) ;
            }
                // $invoice_log->total = $invoice_log->sub_total + $totalTax +  $invoice_log->shipping -  $invoice_log->discount;
            // $invoice_log->total = $invoice_log->sub_total  -  $invoice_log->discount +  $invoice_log->shipping;
            
            $username = Auth::user()->name;
            $invoice_log ->created_by = $username;

            $manager_id = User::where('id','=',auth()->id())->value('manager_id');
            $invoice_log->manager_id = $manager_id;

            $invoice_log->client_id = $request->user_id;

    $invoice_log->body = \App\Invoice\Invoice::where('id','=',$model->id)->get();
        $invoice_log->items = \App\Invoice\Item::where('invoice_id','=',$id)->get();
        
            $invoice_log = DB::transaction(function() use ($invoice_log, $request) {

                $invoice_log->number = counter()->next('invoice');

                $invoice_log->storeHasMany([
                    'items' => $request->items
                ]);

            //  update parent quotation to sales ordered
                if($invoice_log->invoiceable) {
                $invoiceable = $invoice_log->invoiceable;
                if($invoice_log->invoiceable_type == 'App\Quotation\Quotation') {
                        if(in_array($invoiceable->status_id, [Quotation::ACCEPTED, Quotation::SENT])) {
                            $invoiceable->status_id = Quotation::INVOICED;
                            $invoiceable->save();
                        }
                } else if($invoice_log->invoiceable_type == 'App\SalesOrder\SalesOrder') {
                        if(in_array($invoiceable->status_id, [SalesOrder::SENT, SalesOrder::CONFIRMED, SalesOrder::ISSUED])) {
                            $invoiceable->status_id = SalesOrder::CLOSED;
                            $invoiceable->save();
                        }
                }
            }
                return $invoice_log;
            });


        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }
    }

    public function markAs($id, Request $request)
    {
        $model = Invoice::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:1,2,3,6,7,9'
        ]);

        switch ($request->status) {
            case (Invoice::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Invoice::DRAFT
                ]), 404);
                $inv_number = Invoice::where('id','=',$id)->value('number');
                $get_uom = StockMovement::where('purchase_order','=',$inv_number)->value('uom');
                $get_product_id = StockMovement::where('purchase_order','=',$inv_number)->value('product_id');
                // $get_currency = Product::where('id','=',$id)->value('currency_id');
                $get_vendor = StockMovement::where('purchase_order','=',$inv_number)->value('vendor_id');
                $get_category = StockMovement::where('purchase_order','=',$inv_number)->value('category_id');
                $get_sub_category = StockMovement::where('purchase_order','=',$inv_number)->value('sub_category_id');
                $get_warehouse = StockMovement::where('purchase_order','=',$inv_number)->value('warehouse_id');
                $get_name = StockMovement::where('purchase_order','=',$inv_number)->value('product_name');
                $get_code = StockMovement::where('purchase_order','=',$inv_number)->value('product_code');
                $get_qty= StockMovement::where('purchase_order','=',$inv_number)->value('qty');
                $get_price= StockMovement::where('purchase_order','=',$inv_number)->value('price');
             

                $tobepaid = DB::table('clients')
                ->where('id', $model->client_id)->value('to_be_paid');

                DB::table('clients')
                ->where('id', $model->client_id)
                ->update(['to_be_paid' => $tobepaid + $model->total]);

                $client_balance = DB::table('clients')
                ->where('id', $model->client_id)->value('balance');

                DB::table('clients')
                ->where('id', $model->client_id)
                ->update(['balance' => $client_balance + $model->total]);


                $stock_movement = new StockMovement();
                $stock_movement->user_id = auth()->id();
                $stock_movement->product_id = $get_product_id;
                $stock_movement->product_code = $get_code;
                $stock_movement->product_name = $get_name;
                $stock_movement->category_id = $get_category;
                $stock_movement->sub_category_id = $get_sub_category;
                $stock_movement->warehouse_id = $get_warehouse;
                $stock_movement->vendor_id = $get_vendor;
                $stock_movement->qty =  $get_qty;
                $stock_movement->uom = $get_uom;
                $stock_movement->price = $get_price;
                $stock_movement->currency = $request->currency_id;
                $stock_movement->purchase_order = $inv_number;
                $stock_movement->type = "Invoiced Confirmed";
                $stock_movement->created_by = Auth::user()->name;
                $stock_movement->save();

                $company_type = Settings::where('key','=','company_type')->value('value');
                $inv_items = Item::where('invoice_id','=',$model->id)->get();
                foreach($inv_items as $inv_item){
                    if($company_type == 0){
                        $get_product_real_qty = Product::where('id','=',$inv_item->item_id)->value('current_stock');

                        $base_uom_id = \App\Product\Product::where('code','=', $inv_item->item_id)->value('uom_id');
                        if($inv_item->uom_id != $base_uom_id){
                            // throw new \Exception($inv_item->uom_id);
                            $conversion_factor = \App\Product\Conversion::where('product_id','=',$inv_item->item_id)
                            ->where('converted_uom_id','=',$inv_item->uom_id)->value('converted_qty');

                            $converted_qty = round($inv_item->quantity / $conversion_factor);
                            $final_qty = $get_product_real_qty - $conversion_factor;

                        }else{
                            $final_qty = $get_product_real_qty - $inv_item->quantity;
                        }

                        
                        // DB::table('products')
                        // ->where('id', $inv_item->item_id)
                        // ->update(['current_stock' => $final_qty]);

                        // DB::table('product_inventories')
                        // ->where('product_id', $inv_item->item_id)
                        // ->update(['stock_count' => $final_qty]);

                        $has_sales_order = \App\Invoice\Invoice::where('id','=',$id)->value('invoiceable_id');
                        if($has_sales_order > 0)
                        {
                            $on_hold_qty = Product::where('id','=',$inv_item->item_id)->value('on_hold_qty');
                            $current_stock = Product::where('id','=',$inv_item->item_id)->value('current_stock');
                            $base_uom_id = \App\Product\Product::where('code','=', $inv_item->item_id)->value('uom_id');
                            if($inv_item->uom_id != $base_uom_id){
                                // throw new \Exception($inv_item->uom_id);
                                $conversion_factor = \App\Product\Conversion::where('product_id','=',$inv_item->item_id)
                                ->where('converted_uom_id','=',$inv_item->uom_id)->value('converted_qty');

                                $converted_qty = round($inv_item->quantity / $conversion_factor);
                                $final_on_hold_qty = $on_hold_qty - $converted_qty;
                                $final_current_stock  = $current_stock - $converted_qty;
                                
                            }else{
                                $final_on_hold_qty = $on_hold_qty - $inv_item->quantity;
                                $final_current_stock  = $current_stock - $inv_item->quantity;
                            }
                            
                            DB::table('products')
                            ->where('id', $inv_item->item_id)
                            ->update(['on_hold_qty' => $final_on_hold_qty]);

                            DB::table('products')
                            ->where('id', $inv_item->item_id)
                            ->update(['current_stock' => $final_current_stock]);

                            DB::table('product_inventories')
                            ->where('product_id', $inv_item->item_id)
                            ->update(['stock_count' => $final_current_stock]);

                        }else{
                            $current_stock = Product::where('id','=',$inv_item->item_id)->value('current_stock');
                            $base_uom_id = \App\Product\Product::where('code','=', $inv_item->item_id)->value('uom_id');
                            if($inv_item->uom_id != $base_uom_id){
                                
                                $conversion_factor = \App\Product\Conversion::where('product_id','=',$inv_item->item_id)
                                ->where('converted_uom_id','=',$inv_item->uom_id)->value('converted_qty');

                                
                                $converted_qty = round($inv_item->quantity / $conversion_factor);
                                $final_current_stock  = $current_stock - $converted_qty;
                                // throw new \Exception($current_stock .' '. $converted_qty);
                            }else{
                                $final_current_stock  = $current_stock - $inv_item->quantity;
                            }
                            
                            DB::table('products')
                            ->where('id', $inv_item->item_id)
                            ->update(['current_stock' => $final_current_stock]);

                            DB::table('product_inventories')
                            ->where('product_id', $inv_item->item_id)
                            ->update(['stock_count' => $final_current_stock]);
                        }

                    }elseif($company_type == 1){
                        // $get_product_real_qty = FinishedProduct::where('id','=',$inv_item->item_id)->value('current_stock');
                        // $final_qty = $get_product_real_qty - $inv_item->quantity;
                        // DB::table('finished_product')
                        // ->where('id', $inv_item->item_id)
                        // ->update(['current_stock' => $final_qty]);
                    }
                }

                $model->request_date = date('Y-m-d');
                DB::table('invoices')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $invoice_email = Settings::where('key','=','invoices_email')->value('value');
                if($invoice_email == 1){
                    $invoice = Invoice::with(['items.product', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'invoice', $invoice
                    ));
                }

                $invoices_number = Invoice::where('id','=',$id)->value('number');
                $invoices_user = Invoice::where('id','=',$id)->value('user_id');
                $invoices_notification = Settings::where('key','=','invoices_notification')->value('value');
                if($invoices_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $invoices_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $invoices_number;
                    $notification->document_type = 'invoices';
                    $notification->description = 'Invoice '.$invoices_number.' Approved';
                    $notification->link = 'invoices/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }


                $seller_payment = new SellerPayment();
                $seller_payment->number = counter()->next('seller_payments');
                $seller_payment->user_id = $model->user_id;
                $seller_payment->seller_id = $model->seller_id;
                $seller_payment->client_id = $model->client_id;
                $seller_payment->order_id = $id;
                $seller_payment->total_amount = $model->seller_commission;
                $seller_payment->order_amount = $model->total;
                $seller_payment->amount_received = 0;
                $seller_payment->amount_pending = $model->seller_commission;
                $seller_payment->status_id = 2;
                $seller_payment->currency_id = 1;
                $seller_payment->date = date('Y-m-d');
                $seller_payment->year_date = date('Y');
                $seller_payment->payment_at = null;
                $seller_payment->payment_date = null;
                $seller_payment->paid_by = null;
                $seller_payment->payment_mode = null;
                $seller_payment->payment_reference = null;
                $seller_payment->document = null;
                $seller_payment->note = null;
                $seller_payment->save();
                counter()->increment('seller_payments');
                
              

                $seller_balance = Sellers::where('id','=',$model->seller_id)->value('commission_balance');
                Sellers::where('id','=',$model->seller_id)->update(['commission_balance' => $seller_balance + $model->seller_commission]);
                    
            
                
                $model->status_id = Invoice::SENT;

                if($model->posted != 1){
                    $documentData = $model->toArray();
                    $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'invoice');
                    if (!$journalVoucher) {
                        throw new \Exception ("Invoice saved but failed to create journal entries");
                    }else{
                        $model->posted = 0;
                        $journal_id = \App\Invoice\Invoice::where('id','=',$model->id)->value('journal_id');
                        $model->journal_id = $journal_id;
                    }
                }
               

                break;

            case (Invoice::REOPEN) :
                    // must be draft
                    if($model->posted == 1){
                        throw new \Exception ("Delete JV First");
                    }

                    $getPayment_Id = \App\ClientPayment\Item::where('invoice_id','=',$id)->value('client_payment_id');
                    if($getPayment_Id > 0){
                        $amount_received = 0;
                        $allpayments = \App\ClientPayment\Item::where('invoice_id','=',$id)->get();
                        foreach($allpayments as $allpayment){
                            $amount_received += \App\ClientPayment\ClientPayment::where('id','=',$allpayment->client_payment_id)->sum('amount_received');
                        }
                        
                        $payment_date = \App\ClientPayment\ClientPayment::where('id','=',$getPayment_Id)->value('payment_date');
                        $payment_mode = \App\ClientPayment\ClientPayment::where('id','=',$getPayment_Id)->value('payment_mode');
                        $payment_reference = \App\ClientPayment\ClientPayment::where('id','=',$getPayment_Id)->value('payment_reference');
                        $client_id = \App\ClientPayment\ClientPayment::where('id','=',$getPayment_Id)->value('client_id');

                        $new_credit_note = new CreditNote;
                        $new_credit_note->user_id = auth()->id();
                        $new_credit_note->created_by = Auth::user()->name;
                        $new_credit_note->client_id = $client_id;
                        $new_credit_note->number = counter()->next('credit_notes');
                        $new_credit_note->payment_date = $payment_date;
                        $new_credit_note->payment_mode = $payment_mode;
                        $new_credit_note->payment_reference = $payment_reference;
                        $new_credit_note->currency_id = 1;
                        $new_credit_note->amount_received_lbp = 0;
                        $new_credit_note->exchangerate = 1507.5;
                        $new_credit_note->description = "Converted From invoice after reopen";
                        $new_credit_note->amount_received = $amount_received;
                        $new_credit_note->save();
                        counter()->increment('credit_notes');

                        DB::table('invoices')
                        ->where('id', $model->id)
                        ->update(['amount_paid' => 0]);

                        foreach($allpayments as $allpayment){
                            \App\ClientPayment\Item::where('invoice_id','=',$model->id)->delete();
                            \App\ClientPayment\ClientPayment::where('id','=',$allpayment->client_payment_id)->delete();
                        }
                       
                    }
                    
                    $sales_order_items = \App\Invoice\Item::where('invoice_id','=',$id)->get();
                    $has_sales_order = \App\Invoice\Invoice::where('id','=',$id)->value('invoiceable_id');
                    if($has_sales_order > 0){
                        foreach($sales_order_items as $itemS){
                            $on_hold_qty = \App\Product\Product::where('code','=', $itemS->item_id)->value('on_hold_qty');
                
                            $current_stock = \App\Product\Product::where('code','=', $itemS->item_id)->value('current_stock');
                            $base_uom_id = \App\Product\Product::where('code','=', $itemS->item_id)->value('uom_id');
                            if($itemS->uom_id != $base_uom_id){
                                // throw new \Exception($itemS->uom_id);
                                $conversion_factor = \App\Product\Conversion::where('product_id','=',$itemS->item_id)
                                ->where('converted_uom_id','=',$itemS->uom_id)->value('converted_qty');

                                $converted_qty = round($itemS->quantity / $conversion_factor);
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty + $converted_qty]);
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['current_stock' => $current_stock + $converted_qty]);
                                
                            }else{
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['on_hold_qty' => $on_hold_qty + $itemS->quantity]);
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['current_stock' => $current_stock + $itemS->quantity]);
                            }
                        }
    
                    }else{
                        foreach($sales_order_items as $itemS){
                
                            $current_stock = \App\Product\Product::where('code','=', $itemS->item_id)->value('current_stock');
                            $base_uom_id = \App\Product\Product::where('code','=', $itemS->item_id)->value('uom_id');
                            if($itemS->uom_id != $base_uom_id){
                               
                                $conversion_factor = \App\Product\Conversion::where('product_id','=',$itemS->item_id)
                                ->where('converted_uom_id','=',$itemS->uom_id)->value('converted_qty');
//  throw new \Exception($itemS->quantity .' '. $conversion_factor);
                                $converted_qty = round($itemS->quantity / $conversion_factor);
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['current_stock' => $current_stock + $converted_qty]);
                                 DB::table('product_inventories')
                                    ->where('product_id', $itemS->item_id)
                                    ->update(['stock_count' => $current_stock + $converted_qty]);
                            }else{
                                \App\Product\Product::where('code','=', $itemS->item_id)->update(['current_stock' => $current_stock + $itemS->quantity]);
                                DB::table('product_inventories')
                                    ->where('product_id', $itemS->item_id)
                                    ->update(['stock_count' => $current_stock + $itemS->quantity]);
                            }
                        }
                    }
                    
                        $check_if_seller_payment = \App\SellerPayment\SellerPayment::where('order_id','=',$id)->value('id');
                    if($check_if_seller_payment > 0){
                        \App\SellerPayment\SellerPayment::where('order_id','=',$id)->delete();
                    }
                    
                    DB::table('invoices')
                    ->where('id', $model->id)
                    ->update(['status_id' => 1]);
                    
                    $model->status_id = Invoice::DRAFT;
                    
                break;    
            case (Invoice::ADJUSTED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Invoice::PARTIALLY_PAID
                ]), 404);
                
                
                // $getTotal = Invoice::where('id','=',$model->id)->value('total');
                // DB::table('invoices')
                // ->where('id', $model->id)
                // ->update(['amount_paid' => $getTotal, 'status_id' => 4]);
                
                // $model->status_id = Invoice::PAID;
                
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
        if ($user->is_invoices_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Invoice::findOrFail($id);


        $inv_number = Invoice::where('id','=',$id)->value('number');
        $get_uom = StockMovement::where('purchase_order','=',$inv_number)->value('uom');
        $get_product_id = StockMovement::where('purchase_order','=',$inv_number)->value('product_id');
        $get_currency = StockMovement::where('purchase_order','=',$inv_number)->value('currency');
        $get_vendor = StockMovement::where('purchase_order','=',$inv_number)->value('vendor_id');
        $get_category = StockMovement::where('purchase_order','=',$inv_number)->value('category_id');
        $get_sub_category = StockMovement::where('purchase_order','=',$inv_number)->value('sub_category_id');
        $get_warehouse = StockMovement::where('purchase_order','=',$inv_number)->value('warehouse_id');
        $get_name = StockMovement::where('purchase_order','=',$inv_number)->value('product_name');
        $get_code = StockMovement::where('purchase_order','=',$inv_number)->value('product_code');
        $get_qty= StockMovement::where('purchase_order','=',$inv_number)->value('qty');
        $get_price= StockMovement::where('purchase_order','=',$inv_number)->value('price');

        $stock_movement = new StockMovement();
        $stock_movement->user_id = auth()->id();
        $stock_movement->product_id = $get_product_id;
        $stock_movement->product_code = $get_code;
        $stock_movement->product_name = $get_name;
        $stock_movement->category_id = $get_category;
        $stock_movement->sub_category_id = $get_sub_category;
        $stock_movement->warehouse_id = $get_warehouse;
        $stock_movement->vendor_id = $get_vendor;
        $stock_movement->qty =  $get_qty;
        $stock_movement->uom = $get_uom;
        $stock_movement->price = $get_price;
        $stock_movement->currency = $get_currency;
        $stock_movement->purchase_order = $inv_number;
        $stock_movement->type = "Invoiced Deleted";
        $stock_movement->created_by = Auth::user()->name;
        $stock_movement->save();

        // check whether this particular invoice belongs to
        // payments etc.
        $clientPayments = $model->clientPayments()->count();
        $advancePayments = $model->advancePayments()->count();

        $invoice_log = new InvoiceLog();
        $invoice_log->number = $model->number;
        $invoice_log->user_id = $model->user_id;
        $invoice_log->client_id = $model->client_id;
        $invoice_log->date = $model->date;
        $invoice_log->sub_total = $model->sub_total;
        $invoice_log->total = $model->total;
        $invoice_log->currency_id = 1;
        $invoice_log->comment = 'Invoice_Deleted';
            $invoice_log->body = \App\Invoice\Invoice::where('id','=',$model->id)->get();
        $invoice_log->items = \App\Invoice\Item::where('invoice_id','=',$id)->get();
        $invoice_log->save();
        $invouice_item_log = new \App\Invoice\ItemLog;
        $invouice_item_log->invoice_log_id = $invoice_log->id;
        $invouice_item_log->body = \App\Invoice\Item::where('invoice_id','=',$model->id)->get();
        $invouice_item_log->save();
        // if yes provide warning

        if($clientPayments || $advancePayments || !$model->is_editable) {
            return api([
                'message' => 'Delete all the invoice relations first',
                'errors' => []
            ], 422);
        }

        $model->items()->delete();
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
        $query = Invoice::with(['client', 'currency'])->orderby('created_at','desc');
    
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
