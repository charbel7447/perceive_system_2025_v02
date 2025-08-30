<?php

namespace App\Http\Controllers;

use App\AccountItems;
use Illuminate\Http\Request;
use App\Bill\Bill;

use App\Bill\BillLog;

use App\PurchaseOrder\PurchaseOrder;
use App\Vendor;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\Mail\Bills\Send;
use App\Mail\Bills\Confirmed;
use App\Mail\Bills\Declined;
use App\Settings;
use Mail;
use App\User;
use App\Notifications;
use Carbon\Carbon;
use App\Services\JournalService;

class BillController extends Controller
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Bill::with(['vendor', 'currency'])->orderby('created_at','desc')->search()
            ]);
        }
    }

        public function vendor_bills_sent()
    {
        $results = Bill::with('vendor','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','=',2)
            ->limit(15)
            ->get();
            
            return api([
                'results' => $results
            ]);
    }


         public function vendor_bills_confirmed()
    {
        $results = Bill::with('vendor','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->whereIn('status_id', [2, 5,6])
            ->limit(15)
            ->get();
            
            return api([
                'results' => $results
            ]);
    }

    
    
            public function vendor_bills_sent_expenses(Request $request)
    {
        if(request()->has('vendor_id')) {
        $results = Bill::with('vendor','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
            })
            ->where('status_id','=',2)
            ->where('vendor_id','=',$request->vendor_id)
            ->limit(15)
            ->get();
        
            return api([
                'results' => $results
            ]);
            }else{
                throw new \Exception ("select Vendor First !");
            }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_bills_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'vendor_id' => 'sometimes|required|integer|exists:vendors,id'
            ]);
            $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            $bill_field_1 = DB::table('settings')->where('key','=','bill_field_1')->value('value');
            $bill_field_2 = DB::table('settings')->where('key','=','bill_field_2')->value('value');
            $bill_field_3 = DB::table('settings')->where('key','=','bill_field_3')->value('value');
            $bill_field_4 = DB::table('settings')->where('key','=','bill_field_4')->value('value');
            $form = [
                'vendor_id' => null,
                'vendor' => null,
                'number' => counter()->next('bill'),
                'reference' => null,
                'date' => date('Y-m-d'),
                'due_date' => null,
                'terms' => null,
                'note' => null,
                'exchangerate' => $exchange,
                'line1_text' => $bill_field_1,
                'line1_value' => 0,
                'line2_text' => $bill_field_2,
                'line2_value' => 0,
                'line3_text' => $bill_field_3,
                'line3_value' => 0,
                'line4_text' => $bill_field_4,
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
        if ($user->is_bills_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            // 'vendor_id' => 'required|integer',
            // 'currency_id' => 'required|integer|exists:currencies,id',
            'purchase_order_id' => 'sometimes|required|exists:purchase_orders,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable|date_format:Y-m-d',
            'exchangerate' => 'nullable',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer',
            'items.*.vendor_reference' => 'nullable|alpha_dash|max:255',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.tax_name' => 'nullable',
            'items.*.tax_rate' => 'nullable',
            'items.*.qty' => 'nullable|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'terms' => 'nullable|max:2000',
            'note' => 'nullable|max:2000',
            'document' => 'sometimes|nullable|image|max:2048'
        ]);

        $model = new Bill();
        $model->fill($request->except('items'));
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = auth()->id();
        $model->status_id = Bill::DRAFT;
        $model->amount_paid = 0;

         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }

        $model->purchase_order_id = $request->get('purchase_order_id', null);

        $model ->created_by = $username;
        $items = collect($request->items);
        $model->subtotal = $items->sum(function($item) {
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

        if($model->vat_status == 1){
            $model->totaltax = $totalTax;
        }else{
            $model->totaltax = 0 ;
        }
        
      

        $model->total = $model->subtotal + $totalTax;

        $model->request_date = date('Y-m-d');

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
       
        $temp_bill = DB::table('temp_bill')
                                ->where('id','=','1')
                                ->update(['currency' => $request->currency_id ]);

        // $model->total = collect($request->items)->sum(function($item) {
        //     return $item['qty'] * $item['unit_price'];
        // });
        if($request->currency_id == 1){
            $account_item = new AccountItems();
            $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
            $account_item->amount = - ($model->subtotal + $totalTax);
            $account_item->document = 'bill';
            $account_item->type = 'negative';
            $account_item->date = date('Y');
            $account_item->save();
        }else{
            $account_item = new AccountItems();
            $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
            $account_item->amount = - ($model->subtotal + $totalTax);
            $account_item->document = 'bill';
            $account_item->type = 'negative';
            $account_item->date = date('Y');
            $account_item->save();
        }

            // ✅ Get currency once
        $currency = DB::table('temp_bill')->where('id', 1)->value('currency');

        $items->each(function ($item) use ($currency, $totalTax) {
            $amount = ($item['qty'] * $item['unit_price']) + $totalTax;

            if ($currency == 1) {
                // USD
                $balanceUSD = DB::table('accounts')->where('currency_id', 1)->value('balance');
                DB::table('accounts')
                    ->where('currency_id', 1)
                    ->update(['balance' => $balanceUSD - $amount]);
            } else {
                // LBP
                $balanceLBP = DB::table('accounts')->where('currency_id', 2)->value('balance');
                DB::table('accounts')
                    ->where('currency_id', 2)
                    ->update(['balance' => $balanceLBP - $amount]);
            }
        });



        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('bill');

            $model->storeHasMany([
                'items.taxes' => $request->items
            ]);

           //  update parent quotation to sales ordered
            if($model->purchaseOrder) {
               $purchaseOrder = $model->purchaseOrder;
               if(in_array($purchaseOrder->status_id, [PurchaseOrder::SENT, PurchaseOrder::CONFIRMED, PurchaseOrder::RECEIVED])) {
                   $purchaseOrder->status_id = PurchaseOrder::BILLED;
                   $purchaseOrder->save();
               }
           }

            counter()->increment('bill');

            return $model;
        });

       
        $id = $model->id;
        $bill_email = Settings::where('key','=','bills_email')->value('value');
        if($bill_email == 1){
            $bill = Bill::with(['items.product','items.uom', 'vendor', 'currency'])->findOrFail($id);
            Mail::send(new Send(
                $request->only('to', 'bcc','subject', 'message'),
                'bill', $bill
            ));
        }

        $purchase_number = Bill::where('id','=',$id)->value('number');
        $purchase_user = Bill::where('id','=',$id)->value('user_id');
        $purchase_notification = Settings::where('key','=','bills_notification')->value('value');
        if($purchase_notification == 1){
            $manager_id = User::where('id','=',auth()->id())->value('manager_id');
            $username = Auth::user()->name;

            $notification = new Notifications;
            $notification->user_id = auth()->id();
            $notification->manager_id = $manager_id;
            // $notification->manager_id = $purchase_user;
            $notification->number = counter()->next('notifications');
            $notification->document_number = $purchase_number;
            $notification->document_type = 'purchases';
            $notification->description = 'Supplier Invoice '.$purchase_number.' Created';
            $notification->link = 'bills/';
            $notification->document_id = $id;
            $notification->date = now();
            $notification->created_by = $username;
            $notification->status = 'user';
            $notification->save();
            counter()->increment('notifications');
            

        }



        $bill_log = new BillLog();
        $bill_log->fill($request->except('items'));
        $username = Auth::user()->name;
        $bill_log ->created_by = $username;
        $bill_log->user_id = auth()->id();
        $bill_log->status_id = BillLog::DRAFT;
        $bill_log->amount_paid = 0;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $bill_log->document = $fileName;
           }
        }
        $bill_log->purchase_order_id = $request->get('purchase_order_id', null);
        $bill_log ->created_by = $username;
        $items = collect($request->items);
        $bill_log->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });
       
        $bill_log->total = $bill_log->subtotal + $bill_log->totaltax ;
        $bill_log->request_date = date('Y-m-d');
        $bill_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        
            $bill_log->comment = "Store";
         $bill_log->body = \App\Bill\Bill::where('id','=',$model->id)->get();
        $bill_log->items = \App\Bill\Item::where('bill_id','=',$model->id)->get();
        
        $bill_log = DB::transaction(function() use ($bill_log, $request) {
            $bill_log->number = counter()->next('bill');
            $bill_log->storeHasMany([
                'items' => $request->items
            ]);
            if($bill_log->purchaseOrder) {
               $purchaseOrder = $bill_log->purchaseOrder;
               if(in_array($purchaseOrder->status_id, [PurchaseOrder::SENT, PurchaseOrder::CONFIRMED, PurchaseOrder::RECEIVED])) {
                   $purchaseOrder->status_id = PurchaseOrder::BILLED;
                   $purchaseOrder->save();
               }
           }
            return $bill_log;
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
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = Bill::with([
                'items.product', 'items.uom', 'items.taxes', 'vendor', 'currency', 'purchaseOrder',
                'vendorPayments.parent.currency'
            ])->findOrFail($id);
            return api([
                'data' => $data
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_bills_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $user = auth()->user();
        if ($user->is_vendorbill_create == 0 && $user->is_admin != 1){
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $data = Bill::with(['items.product','items.taxes','items.uom', 'vendor', 'currency', 'purchaseOrder'])
            ->findOrFail($id);
        return pdf('docs.bill', $data);
            }
        }
    }

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_bills_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = Bill::with(['items.taxes','items.product','items.uom', 'vendor', 'currency'])->findOrFail($id);

        if($request->has('mode')) {
            switch ($request->mode) {
                case 'clone':

                    $form->number = counter()->next('bill');
                    $form->date = null;
                    $form->due_date = null;
                    $form->reference = null;
                    unset($form->purchase_order_id);

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
        if ($user->is_bills_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Bill::findOrFail($id);

        // abort if not editable
        abort_if(!$model->is_editable, 404);

        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'reference' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable|date_format:Y-m-d',
            'exchangerate' => 'nullable|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|integer|exists:bill_items,id,bill_id,'.$model->id,
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.vendor_reference' => 'required|alpha_dash|max:255',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_name' => 'nullable',
            'items.*.tax_rate' => 'nullable',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom_id' => 'nullable',
            'terms' => 'nullable|max:2000',
            'note' => 'nullable|max:2000',
            'document' => 'sometimes|required|image|max:2048'
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

        // $model->total = collect($request->items)->sum(function($item) {
        //     return $item['qty'] * $item['unit_price'];
        // });
        $items = collect($request->items);
        $model->subtotal = $items->sum(function($item) {
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

        if($model->vat_status == 1){
            $model->totaltax = $totalTax;
        }else{
            $model->totaltax = 0 ;
        }

        $model->total = $model->subtotal + $totalTax ;

        $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;

        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model = DB::transaction(function() use ($model, $request) {

            $model->updateHasMany([
                'items.taxes' => $request->items
            ]);

            return $model;
        });


        $bill_log = new BillLog();
        $bill_log->fill($request->except('items'));
        $username = Auth::user()->name;
        $bill_log ->created_by = $username;
        $bill_log->user_id = auth()->id();
        $bill_log->status_id = BillLog::SENT;
        $bill_log->amount_paid = 0;
         // upload document if exists
        if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $bill_log->document = $fileName;
           }
        }
        
             $bill_log->comment = "Update";
         $bill_log->body = \App\Bill\Bill::where('id','=',$model->id)->get();
        $bill_log->items = \App\Bill\Item::where('bill_id','=',$model->id)->get();
        
        $bill_log->purchase_order_id = $request->get('purchase_order_id', null);
        $bill_log ->created_by = $username;
        $items = collect($request->items);
        $bill_log->subtotal = $items->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });
        if($bill_log->vat_status == 1){
            $bill_log->totaltax = $items->sum(function($item) {
                return ($item['qty'] * $item['unit_price'] * $item['tax_rate']) / 100;
            });
        }else{
            $bill_log->totaltax = 0 ;
        }
        $bill_log->total = $bill_log->subtotal + $bill_log->totaltax ;
        $bill_log->request_date = date('Y-m-d');
        $bill_log->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
        $bill_log = DB::transaction(function() use ($bill_log, $request) {
            $bill_log->number = counter()->next('bill');
            $bill_log->storeHasMany([
                'items' => $request->items
            ]);
            if($bill_log->purchaseOrder) {
               $purchaseOrder = $bill_log->purchaseOrder;
               if(in_array($purchaseOrder->status_id, [PurchaseOrder::SENT, PurchaseOrder::CONFIRMED, PurchaseOrder::RECEIVED])) {
                   $purchaseOrder->status_id = PurchaseOrder::BILLED;
                   $purchaseOrder->save();
               }
           }
            return $bill_log;
        });
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
      }
    }

    public function markAs($id, Request $request)
    {
        $model = Bill::findOrFail($id);
        $request->validate([
            'status' => 'required|integer|in:1,2,3,5,6,7'
        ]);

        switch ($request->status) {
            
            case (Bill::SENT) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Bill::DRAFT
                ]), 404);

                $model->request_date = date('Y-m-d');
                DB::table('bills')
                    ->where('id', $id)
                    ->update(['request_date' => date('Y-m-d')]);

                $bill_email = Settings::where('key','=','bills_email')->value('value');
                if($bill_email == 1){
                    $bill = Bill::with(['items.product','items.uom', 'vendor', 'currency'])->findOrFail($id);
                    Mail::send(new Send(
                        $request->only('to', 'bcc','subject', 'message'),
                        'bill', $bill
                    ));
                }

                if($model->posted != 1){
                    $documentData = $model->toArray();
                    $journalVoucher = $this->journalService->create_journal_voucher($documentData, 'bill');
                    if (!$journalVoucher) {
                        throw new \Exception ("Bill saved but failed to create journal entries");
                    }else{
                        $model->posted = 0;
                        $journal_id = \App\Bill\Bill::where('id','=',$model->id)->value('journal_id');
                        $model->journal_id = $journal_id;
                    }
                }

                $model->status_id = Bill::SENT;
                break;

            case (Bill::ADJUSTED) :
                    // must be draft
                    abort_if(!in_array($model->status_id, [
                        Bill::PARTIALLY_PAID
                    ]), 404);
                    
                    
                    $getTotal = Bill::where('id','=',$model->id)->value('total');
                    DB::table('bills')
                    ->where('id', $model->id)
                    ->update(['amount_paid' => $getTotal, 'status_id' => 3]);
                    
                    $model->status_id = Bill::PAID;
                    
                    break;

            case (Bill::CONFIRMED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Bill::SENT
                ]), 404);
    
                $model->confirmed_date = date('Y-m-d');
                DB::table('bills')
                    ->where('id', $id)
                    ->update(['confirmed_date' => date('Y-m-d')]);

                $bill_email = Settings::where('key','=','bills_email')->value('value');
                if($bill_email == 1){
                    $bill = Bill::with(['items.product','items.uom', 'vendor', 'currency'])->findOrFail($id);
                    Mail::send(new Confirmed(
                        $request->only('to', 'bcc','subject', 'message'),
                        'bill', $bill
                    ));
                }

                $vendor_id = $model->vendor_id;
                $old_balance = \App\Vendor::where('id','=',$vendor_id)->value('balance');
                \App\Vendor::where('id','=',$vendor_id)->update(['balance' => $old_balance + $model->total]);

                $purchase_number = Bill::where('id','=',$id)->value('number');
                $purchase_user = Bill::where('id','=',$id)->value('user_id');
                $purchase_notification = Settings::where('key','=','bills_notification')->value('value');
                if($purchase_notification == 1){
                    $manager_id = User::where('id','=',auth()->id())->value('manager_id');
                    $username = Auth::user()->name;

                    $notification = new Notifications;
                    $notification->user_id = auth()->id();
                    $notification->manager_id = $manager_id;
                    // $notification->manager_id = $purchase_user;
                    $notification->number = counter()->next('notifications');
                    $notification->document_number = $purchase_number;
                    $notification->document_type = 'purchases';
                    $notification->description = 'Supplier Invoice '.$purchase_number.' Approved';
                    $notification->link = 'bills/';
                    $notification->document_id = $id;
                    $notification->date = now();
                    $notification->created_by = $username;
                    $notification->status = 'user';
                    counter()->increment('notifications');
                    $notification->save();

                }

                $model->status_id = 6;
                break;
            
            case (Bill::PAID) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    Bill::CONFIRMED
                ]), 404);


                $model->status_id = Bill::PAID;
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
        if ($user->is_bills_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Bill::findOrFail($id);

        $bill_log = new BillLog();
        $bill_log->reference = $id;
        $bill_log->number = $model->number;
        $bill_log->vendor_id = $model->vendor_id;
        $bill_log->total = $model->total;
        $bill_log->subtotal = $model->subtotal;
        $bill_log->currency_id = $model->currency_id;
        $bill_log->user_id = $model->user_id;
        $bill_log->date = $model->date;
        $bill_log->totaltax = $model->totaltax;
        $bill_log->status_id = $model->status_id;
        
        $bill_log->purchase_order_id = $model->purchase_order_id;
        $bill_log->comment = \App\Bill\Item::where('bill_id','=',$id)->get();
             $bill_log->comment = "Deleted";
         $bill_log->body = \App\Bill\Bill::where('id','=',$model->id)->get();
        $bill_log->items = \App\Bill\Item::where('bill_id','=',$model->id)->get();
        $bill_log->save();
        
        // check whether this particular bill belongs to
        $vendorPayments = $model->vendorPayments()->count();

        // if yes provide warning

        if($vendorPayments || !$model->is_editable) {
            return api([
                'message' => 'Delete all the bill relations first',
                'errors' => []
            ], 422);
        }

        if($model->status_id > 2){
                   $vendor_id = $model->vendor_id;
                $old_balance = \App\Vendor::where('id','=',$vendor_id)->value('balance');
                \App\Vendor::where('id','=',$vendor_id)->update(['balance' => $old_balance - $model->total]);
                
        }
        $model->items()->delete();
        $model->delete();

        return api([
            'deleted' => true
        ]);
            }
    }

  
}
