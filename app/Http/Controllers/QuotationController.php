<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quotation\Quotation;
use App\Quotation\QuotationLog;

use App\Client;
use DB;
use Auth;
use App\DeliveryCondition;
use App\PaymentCondition;
use App\ExchangeRate\ExchangeRate;
use App\VatRate\VatRate;
use App\Mail\Quotations\Send;
use App\Mail\Quotations\Confirmed;
use App\Mail\Quotations\Declined;

// use App\Support\Settings;
use App\Settings;
use App\User;
use App\Notifications;
use Mail;

class QuotationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => Quotation::with(['client', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_quotations_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'client_id' => 'sometimes|required|integer'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $vatrate = VatRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $quotation_field_1 = DB::table('settings')->where('key','=','quotation_field_1')->value('value');
        $quotation_field_2 = DB::table('settings')->where('key','=','quotation_field_2')->value('value');
        $quotation_field_3 = DB::table('settings')->where('key','=','quotation_field_3')->value('value');
        $quotation_field_4 = DB::table('settings')->where('key','=','quotation_field_4')->value('value');
        $form = [
            'client_id' => null,
            'client' => null,
            'number' => counter()->next('quotation'),
            'reference' => null,
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'delivery_date' => date('Y-m-d'),
            'paymentcondition_id' => null,
            'deliverycondition_id' => null,
            'exchangerate' => $exchange,
            'vatrate' => $vatrate,
            'terms' => null,
            'discount' => 0,
            'shipping' => 0,
            'line1_text' => $quotation_field_1,
            'line1_value' => 0,
            'line2_text' => $quotation_field_2,
            'line2_value' => 0,
            'line3_text' => $quotation_field_3,
            'line3_value' => 0,
            'line4_text' => $quotation_field_4,
            'line4_value' => 0,
            'items' => [
                [
                    'product' => null,
                    'product_id' => null,
                    'unit_price' => 0,
                    'qty' => 1,
                    'uom'=> 0,
                    'taxes' => []
                ]
            ]
        ];
        if($request->has('client_id')) {
            $client = Client::with(['currency'])->findOrFail($request->client_id);
            
            array_set($form, 'client_id', $client->id);
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
        if ($user->is_quotations_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'delivery_date' => 'required|date_format:Y-m-d',
            'paymentcondition_id' => 'required',
            'deliverycondition_id' => 'required',
            'discount' => 'nullable',
            'shipping' => 'nullable',
            'vat_status' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'items.*.taxes.*.name' => 'required|max:255',
            'items.*.taxes.*.rate' => 'required|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'required|max:255',
            'terms' => 'nullable|max:2000'
        ]);

        $model = new Quotation();
       
        $model->fill($request->except('items'));

      

        $model->user_id = auth()->id();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;
        $model->status_id = Quotation::DRAFT;

        $items = collect($request->items);
       
        $model->sub_total = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });


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

            //discount
            $model->discount = $request->discount;

            //shipping
            $model->shipping = $request->shipping;
            // total tax
             
            if($request->vat_status == 0)
            {
                $model->total = $model->sub_total+  $model->shipping -  $model->discount ;
            } else {
                $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            }

            // $model->total = $model->sub_total +  $model->shipping -  $model->discount ;
            $username = Auth::user()->name;
            $model ->created_by = $username;

        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('quotation');
            $model->storeHasMany([
                'items.taxes' => $request->items
            ]);

            counter()->increment('quotation');

            return $model;
        });

        $quotation_log = new QuotationLog();
       
        $quotation_log->fill($request->except('items'));

      

        $quotation_log->user_id = auth()->id();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $quotation_log->manager_id = $manager_id;
        $quotation_log->status_id = Quotation::DRAFT;

        $items = collect($request->items);
       
       
       $quotation_log->comment = "Store";
           $quotation_log->body = \App\Quotation\Quotation::where('id','=',$model->id)->get();
        $quotation_log->items = \App\Quotation\Item::where('quotation_id','=',$model->id)->get();
        
        $quotation_log->sub_total = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });


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

            //discount
            $quotation_log->discount = $request->discount;

            //shipping
            $quotation_log->shipping = $request->shipping;
            // total tax
             
            if($request->vat_status == 0)
            {
                $quotation_log->total = $quotation_log->sub_total+  $quotation_log->shipping -  $quotation_log->discount ;
            } else {
                $quotation_log->total = $quotation_log->sub_total + $totalTax  +  $quotation_log->shipping -  $quotation_log->discount ;
            }

            // $quotation_log->total = $quotation_log->sub_total +  $quotation_log->shipping -  $quotation_log->discount ;
            $username = Auth::user()->name;
            $quotation_log ->created_by = $username;

        $quotation_log = DB::transaction(function() use ($quotation_log, $request) {

            $quotation_log->number = counter()->next('quotation');
            $quotation_log->storeHasMany([
                'items' => $request->items
            ]);
            return $quotation_log;
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
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = Quotation::with([
                'items.product', 'items.taxes', 'items.uom', 'client', 'currency',
                'advancePayments.currency','paymentcondition','deliverycondition'
            ])
            ->findOrFail($id);
        return api([
            'data' => $data
        ]);
    }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_quotations_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = Quotation::with([
                'items.product', 'items.taxes', 'client', 'currency',
                'advancePayments.currency','paymentcondition','deliverycondition'
            ])
            ->findOrFail($id);
        return pdf('docs.quotation', $data);}
    }

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_quotations_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = Quotation::with(['items.uom','items.product', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('quotation');
                    $form->date = date('Y-m-d');
                    $form->reference = null;

                    break;

                case 'sales_order':

                    $form->number = counter()->next('sales_order');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    $form->quotation_id = $form->id;
                    break;

                case 'invoice':

                    $form->number = counter()->next('invoice');
                    $form->date = date('Y-m-d');
                    $form->reference = null;
                    $form->parent_id = $form->id;
                    $form->parent_type = 'quotation';
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
        if ($user->is_quotations_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Quotation::findOrFail($id);

        // abort if not editable
        abort_if(!$model->is_editable, 404);

        $request->validate([
            'client_id' => 'required|integer',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable|date_format:Y-m-d',
            'delivery_date' => 'nullable|date_format:Y-m-d',
            'paymentcondition_id' => 'nullable',
            'deliverycondition_id' => 'nullable',
            'discount' => 'nullable',
            'shipping' => 'nullable',
            'vat_status' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|integer|exists:quotation_items,id,quotation_id,'.$model->id,
            'items.*.product_id' => 'required|integer',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'items.*.taxes.*.name' => 'required|max:255',
            'items.*.taxes.*.rate' => 'required|numeric|min:0',
            'items.*.taxes.*.tax_authority' => 'required|max:255',
            'terms' => 'nullable|max:2000'
        ]);

        $model->fill($request->except('items'));

        $items = collect($request->items);
       
        $model->sub_total = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

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

        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $model->manager_id = $manager_id;
          //discount
        $model->discount = $request->discount;
        //shipping
        $model->shipping = $request->shipping;
        // total tax
            if($request->vat_status == 0)
            {
                $model->total = $model->sub_total+  $model->shipping -  $model->discount ;
            } else {
                $model->total = $model->sub_total + $totalTax  +  $model->shipping -  $model->discount ;
            }

        // $model->total = $model->sub_total + $totalTax +  $model->shipping -  $model->discount;
        //$model->total = $model->sub_total +  $model->shipping -  $model->discount ;
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items.taxes' => $request->items
            ]);

            return $model;
        });
        $quotation_log = new QuotationLog();
       
        $quotation_log->fill($request->except('items'));

      

        $quotation_log->user_id = auth()->id();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $quotation_log->manager_id = $manager_id;
        $quotation_log->status_id = Quotation::DRAFT;

        $items = collect($request->items);
       
        $quotation_log->sub_total = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });
  $quotation_log->comment = "Update";
           $quotation_log->body = \App\Quotation\Quotation::where('id','=',$model->id)->get();
        $quotation_log->items = \App\Quotation\Item::where('quotation_id','=',$model->id)->get();

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

            //discount
            $quotation_log->discount = $request->discount;

            //shipping
            $quotation_log->shipping = $request->shipping;
            // total tax
             
            if($request->vat_status == 0)
            {
                $quotation_log->total = $quotation_log->sub_total+  $quotation_log->shipping -  $quotation_log->discount ;
            } else {
                $quotation_log->total = $quotation_log->sub_total + $totalTax  +  $quotation_log->shipping -  $quotation_log->discount ;
            }

            // $quotation_log->total = $quotation_log->sub_total +  $quotation_log->shipping -  $quotation_log->discount ;
            $username = Auth::user()->name;
            $quotation_log ->created_by = $username;

        $quotation_log = DB::transaction(function() use ($quotation_log, $request) {

            $quotation_log->number = counter()->next('quotation');
            $quotation_log->storeHasMany([
                'items' => $request->items
            ]);
            return $quotation_log;
        });

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }
    }

    public function markAs($id, Request $request)
    {
        $model = Quotation::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:2,3,4'
        ]);

        switch ($request->status) {
            case (Quotation::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Quotation::DRAFT
                ]), 404);

                $model->request_date = date('Y-m-d');
                DB::table('quotations')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $quotation_number = Quotation::where('id','=',$id)->value('number');
                $quotation_email = Settings::where('key','=','quotations_email')->value('value');
                if($quotation_email == 1){
                    $quotation = Quotation::with(['items.product', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'quotation', $quotation
                    ));
                }
                
                $quotation_notification = Settings::where('key','=','quotations_notification')->value('value');
                if($quotation_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $quotation_number;
                    $notification->document_type = 'quotations';
                    $notification->description = 'New Quotation Created '.$quotation_number;
                    $notification->link = 'quotations/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'manager';
                    counter()->increment('notifications');
                    $notification->save();

                }
                

                
                $model->status_id = Quotation::SENT;
                break;

            case (Quotation::ACCEPTED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Quotation::DRAFT, Quotation::SENT,
                    Quotation::DECLINED
                ]), 404);

                $model->confirmed_date = date('Y-m-d');
                DB::table('quotations')
                    ->where('id', $id)
                    ->update(['confirmed_date' => date('Y-m-d')]);

                $quotation_email = Settings::where('key','=','quotations_email')->value('value');
                if($quotation_email == 1){
                    $quotation = Quotation::with(['items.product', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Confirmed(
                        $request->only('to', 'bcc','subject', 'message'),
                        'quotation', $quotation
                    ));
                }


                $quotation_number = Quotation::where('id','=',$id)->value('number');
                $quotation_user = Quotation::where('id','=',$id)->value('user_id');
                $quotation_email = Settings::where('key','=','quotations_email')->value('value');
                $quotation_notification = Settings::where('key','=','quotations_notification')->value('value');
                if($quotation_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $quotation_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $quotation_number;
                    $notification->document_type = 'quotations';
                    $notification->description = 'Quotation Approved '.$quotation_number;
                    $notification->link = 'quotations/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                
                $model->status_id = Quotation::ACCEPTED;
                break;

            case (Quotation::DECLINED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Quotation::DRAFT, Quotation::SENT,
                    Quotation::ACCEPTED
                ]), 404);

                $model->declined_date = date('Y-m-d');
                DB::table('quotations')
                    ->where('id', $id)
                    ->update(['declined_date' => date('Y-m-d')]);

                $quotation_email = Settings::where('key','=','quotations_email')->value('value');
                if($quotation_email == 1){
                    $quotation = Quotation::with(['items.product', 'items.taxes', 'client', 'currency','paymentcondition','deliverycondition'])->findOrFail($id);
                    Mail::send(new Declined(
                        $request->only('to', 'bcc','subject', 'message'),
                        'quotation', $quotation
                    ));
                }

                $quotation_number = Quotation::where('id','=',$id)->value('number');
                $quotation_user = Quotation::where('id','=',$id)->value('user_id');
                $quotation_email = Settings::where('key','=','quotations_email')->value('value');
                $quotation_notification = Settings::where('key','=','quotations_notification')->value('value');
                if($quotation_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    // $notification->manager_id = $manager_id;
                    $notification->manager_id = $quotation_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $quotation_number;
                    $notification->document_type = 'quotations';
                    $notification->description = 'Quotation Declined '.$quotation_number;
                    $notification->link = 'quotations/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                
                $model->status_id = Quotation::DECLINED;
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
            'is_editable' => $model->is_editable
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_quotations_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Quotation::findOrFail($id);

        // check whether this particular quotation belongs to
        $invoices = $model->invoices()->count();
        $salesOrders = $model->salesOrders()->count();
        $advancePayments = $model->advancePayments()->count();
        // invoice, etc.
        // if yes provide warning

    $quotation_log = new QuotationLog();
        $quotation_log->number = $model->number;
        $quotation_log->user_id = $model->user_id;
        $quotation_log->client_id = $model->client_id;
        $quotation_log->date = $model->date;
        $quotation_log->sub_total = $model->sub_total;
        $quotation_log->total = $model->total;
        $quotation_log->currency_id = 1;
        $quotation_log->comment = 'Qotation_Deleted';
            $quotation_log->body = \App\Quotation\Quotation::where('id','=',$model->id)->get();
        $quotation_log->items = \App\Quotation\Item::where('quotation_id','=',$model->id)->get();
        $quotation_log->save();
        
        if($invoices || $salesOrders || $advancePayments || !$model->is_editable) {
            return api([
                'message' => 'Delete all the quotation relations first',
                'errors' => []
            ], 422);
        }

        $model->items()->delete();
        $model->delete();

        return api([
            'deleted' => true
        ]);}
    }
}
