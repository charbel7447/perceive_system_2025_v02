<?php

    namespace App\Http\Controllers;
    
    use App\AccountItems;
    use Illuminate\Http\Request;
    use App\ShipperBill\ShipperBill;
    use App\ContainerOrder\ContainerOrder;
    use App\Shipper;
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
    
    class ShipperBillController extends Controller
    {
        public function index()
        {
            $user = auth()->user();
            if ($user->is_Shippers_Bills_view == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
                return api([
                    'data' => ShipperBill::with(['shipper', 'currency'])->search()
                ]);
            }
        }
    
        public function create(Request $request)
        {
            $user = auth()->user();
            if ($user->is_Shippers_Bills_create == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
                $request->validate([
                    'shipper_id' => 'sometimes|required|integer|exists:shippers,id'
                ]);
                $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->get();
                foreach($exchange as $rate1){
                $form = [
                    'shipper_id' => null,
                    'shipper' => null,
                    'number' => counter()->next('shipper_bill'),
                    'reference' => null,
                    'date' => null,
                    'due_date' => null,
                    'terms' => null,
                    'note' => null,
                    'exchangerate' => $rate1->value2,
                    'items' => [
                        [
                            'product' => null,
                            'product_id' => null,
                            'vendor_reference' => null,
                            'price' => 0,
                            'unit_price' => 0,
                            'quantity' => 1,
                            'uom'=> 0,
                            'tax_name' => 'Vat',
                            'tax_rate' => 0,
                        ]
                    ]
                ];
                }
    
                if($request->has('shipper_id')) {
                    $shipper = Shipper::with(['currency'])->findOrFail($request->shipper_id);
    
                    array_set($form, 'shipper_id', $shipper->id);
                    array_set($form, 'shipper', $shipper);
                    array_set($form, 'currency_id', $shipper->currency->id);
                    array_set($form, 'currency', $shipper->currency);
    
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
            if ($user->is_Shippers_Bills_create == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
            $request->validate([
                'shipper_id' => 'required|integer|exists:shippers,id',
                'currency_id' => 'required|integer|exists:currencies,id',
                'container_order_id' => 'sometimes|required|exists:container_orders,id',
                'reference' => 'nullable|max:255',
                'date' => 'required|date_format:Y-m-d',
                'due_date' => 'required|date_format:Y-m-d',
                'exchangerate' => 'nullable',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer',
                'items.*.vendor_reference' => 'nullable|alpha_dash|max:255',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.tax_name' => 'nullable',
                'items.*.tax_rate' => 'nullable',
                'items.*.quantity' => 'required|numeric|min:0',
                'items.*.uom_id' => 'nullable',
                'terms' => 'nullable|max:2000',
                'note' => 'nullable|max:2000',
                'document' => 'sometimes|required|image|max:2048'
            ]);
    
            $model = new ShipperBill();
            $model->fill($request->except('items'));
            $username = Auth::user()->name;
            $model ->created_by = $username;
            $model->user_id = auth()->id();
            $model->status_id = ShipperBill::SENT;
            $model->amount_paid = 0;
    
             // upload document if exists
            if($request->hasFile('document') && $request->file('document')->isValid()) {
                // store in public uploads folder by default
               if($fileName = uploadFile($request->document)) {
                    $model->document = $fileName;
               }
            }
    
            $model->container_order_id = $request->get('container_order_id', null);
    
            $model ->created_by = $username;
            $items = collect($request->items);
            $model->subtotal = $items->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            });
            if($model->vat_status == 1){
                $model->totaltax = $items->sum(function($item) {
                    return ($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100;
                });
            }else{
                $model->totaltax = 0 ;
            }
            
    
    
            $model->total = $model->subtotal + $model->totaltax ;
            $model->request_date = date('Y-m-d');
    
            $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
           
            $temp_bill = DB::table('temp_bill')
                                    ->where('id','=','1')
                                    ->update(['currency' => $request->currency_id ]);
    
            // $model->total = collect($request->items)->sum(function($item) {
            //     return $item['quantity'] * $item['unit_price'];
            // });
            
            if($request->currency_id == 1){
                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
                $account_item->amount = - ($model->subtotal + $model->totaltax);
                $account_item->document = 'Shipperbill';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();
            }else{
                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
                $account_item->amount = - ($model->subtotal + $model->totaltax);
                $account_item->document = 'Shipperbill';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();
            }
    
            // get only applied greater than zero
            $items = collect($request->items)->map(function($item) {
                // $total_amount_applied = $item['amount_applied'] + ( $item['amount_applied_lbp'] * $item['amount_applied_lbp_rate']) + ( $item['amount_applied_vat'] * $item['amount_applied_vat_rate']);
                $currency = DB::table('temp_bill')->where('id','=','1')->value('currency');
    
                 if($currency == 1){
                    $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
                    $updateAccountUSD = DB::table('accounts')
                                    ->where('currency_id','=','1')
                                    ->update(['balance' => $balanceUSD - ($item['quantity'] * $item['unit_price']  + (($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100)) ]);
                //     $account_item = new AccountItems();
                //     $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
                //     $account_item->amount = ($item['quantity'] * $item['unit_price']) + (($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100);
                //     $account_item->document = 'bill';
                //     $account_item->type = 'negative';
                //     $account_item->date = date('Y');
                //     $account_item->save();
                 }else{
                    $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
                    $updateAccountLBP = DB::table('accounts')
                                        ->where('currency_id','=','2')
                                        ->update(['balance' => $balanceLBP - ($item['quantity'] * $item['unit_price']  + (($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100)) ]);
                //     $account_item = new AccountItems();
                //     $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
                //     $account_item->amount = ($item['quantity'] * $item['unit_price'] ) + (($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100);
                //     $account_item->document = 'bill';
                //     $account_item->type = 'negative';
                //     $account_item->date = date('Y');
                //     $account_item->save();
                 }
            });
    
    
    
            $model = DB::transaction(function() use ($model, $request) {
    
                $model->number = counter()->next('shipper_bill');
    
                $model->storeHasMany([
                    'items' => $request->items
                ]);
    
               //  update parent quotation to sales ordered
                if($model->containerOrder) {
                   $containerOrder = $model->containerOrder;
                   if(in_array($containerOrder->status_id, [ContainerOrder::SENT, ContainerOrder::CONFIRMED, ContainerOrder::RECEIVED])) {
                       $containerOrder->status_id = ContainerOrder::BILLED;
                       $containerOrder->save();
                   }
               }
    
                counter()->increment('shipper_bill');
    
                return $model;
            });
    
           
            $id = $model->id;
            $bill_email = Settings::where('key','=','bills_email')->value('value');
            if($bill_email == 1){
                $shipper_bills = ShipperBill::with(['items.product','items.uom', 'shipper', 'currency'])->findOrFail($id);
                Mail::send(new Send(
                    $request->only('to', 'bcc','subject', 'message'),
                    'shipper_bills', $shipper_bills
                ));
            }
    
            $purchase_number = ShipperBill::where('id','=',$id)->value('number');
            $purchase_user = ShipperBill::where('id','=',$id)->value('user_id');
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
                $notification->document_type = 'shipper_bills';
                $notification->description = 'Supplier Invoice '.$purchase_number.' Created';
                $notification->link = 'shipper_bills/';
                $notification->document_id = $id;
                $notification->date = now();
                $notification->created_by = $username;
                $notification->status = 'user';
                $notification->save();
                counter()->increment('notifications');
                
    
            }
    
            return api([
                'saved' => true,
                'id' => $model->id
            ]);
                }
        }
    
        public function show($id)
        {
            $user = auth()->user();
            if ($user->is_Shippers_Bills_view == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
                $data = ShipperBill::with([
                    'items.product', 'items.uom', 'shipper', 'currency', 'containerOrder',
                    'shipperPayments.parent.currency'
                ])->findOrFail($id);
                return api([
                    'data' => $data
                ]);
            }
        }
    
        public function pdf($id)
        {
            $user = auth()->user();
            if ($user->is_Shippers_Bills_view == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
            $user = auth()->user();
            if ($user->is_vendorbill_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
                }else{
            $data = ShipperBill::with(['items.product','items.uom', 'shipper', 'currency', 'containerOrder'])
                ->findOrFail($id);
            return pdf('docs.shipper_bill', $data);
                }
            }
        }
    
        public function edit($id, Request $request)
        {
            $user = auth()->user();
            if ($user->is_Shippers_Bills_edit == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
            $form = ShipperBill::with(['items.product','items.uom', 'shipper', 'currency'])->findOrFail($id);
    
            if($request->has('mode')) {
                switch ($request->mode) {
                    case 'clone':
    
                        $form->number = counter()->next('shipper_bill');
                        $form->date = null;
                        $form->due_date = null;
                        $form->reference = null;
                        unset($form->container_order_id);
    
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
            if ($user->is_Shippers_Bills_edit == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
            $model = ShipperBill::findOrFail($id);
    
            // abort if not editable
            abort_if(!$model->is_editable, 404);
    
            $request->validate([
                'shipper_id' => 'required|integer|exists:shippers,id',
                'currency_id' => 'required|integer|exists:currencies,id',
                'reference' => 'nullable|max:255',
                'date' => 'required|date_format:Y-m-d',
                'due_date' => 'required|date_format:Y-m-d',
                'exchangerate' => 'nullable|max:255',
                'items' => 'required|array|min:1',
                'items.*.id' => 'sometimes|integer|exists:bill_items,id,bill_id,'.$model->id,
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.vendor_reference' => 'required|alpha_dash|max:255',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.tax_name' => 'nullable',
                'items.*.tax_rate' => 'nullable',
                'items.*.quantity' => 'required|numeric|min:0',
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
            //     return $item['quantity'] * $item['unit_price'];
            // });
            $items = collect($request->items);
            $model->subtotal = $items->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            });
            if($model->vat_status == 1){
                $model->totaltax = $items->sum(function($item) {
                    return ($item['quantity'] * $item['unit_price'] * $item['tax_rate']) / 100;
                });
            }else{
                $model->totaltax = 0 ;
            }
    
            $model->total = $model->subtotal + $model->totaltax ;
    
            $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
    
            $username = Auth::user()->name;
            $model ->created_by = $username;
            $model = DB::transaction(function() use ($model, $request) {
    
                $model->updateHasMany([
                    'items' => $request->items
                ]);
    
                return $model;
            });
    
            return api([
                'saved' => true,
                'id' => $model->id
            ]);
          }
        }
    
        public function markAs($id, Request $request)
        {
            $model = ShipperBill::findOrFail($id);
            $request->validate([
                'status' => 'required|integer|in:1,2,3,6,7'
            ]);
    
            switch ($request->status) {
                
                case (ShipperBill::SENT) :
                    // must be draft
                    abort_if(!in_array($model->status_id, [
                        ShipperBill::DRAFT
                    ]), 404);
    
                    $model->request_date = date('Y-m-d');
                    DB::table('shipper_bills')
                        ->where('id', $id)
                        ->update(['request_date' => date('Y-m-d')]);
    
                    $bill_email = Settings::where('key','=','bills_email')->value('value');
                    if($bill_email == 1){
                        $bill = ShipperBill::with(['items.product','items.uom', 'shipper', 'currency'])->findOrFail($id);
                        Mail::send(new Send(
                            $request->only('to', 'bcc','subject', 'message'),
                            'bill', $bill
                        ));
                    }
    
                    $model->status_id = ShipperBill::SENT;
                    break;
    
                case (ShipperBill::ADJUSTED) :
                        // must be draft
                        abort_if(!in_array($model->status_id, [
                            ShipperBill::PARTIALLY_PAID
                        ]), 404);
                        
                        
                        $getTotal = ShipperBill::where('id','=',$model->id)->value('total');
                        DB::table('shipper_bills')
                        ->where('id', $model->id)
                        ->update(['amount_paid' => $getTotal, 'status_id' => 3]);
                        
                        $model->status_id = ShipperBill::PAID;
                        
                        break;
    
                case (ShipperBill::CONFIRMED) :
                    // must be draft
                    abort_if(!in_array($model->status_id, [
                        ShipperBill::DRAFT
                    ]), 404);
        
                    $model->confirmed_date = date('Y-m-d');
                    DB::table('shipper_bills')
                        ->where('id', $id)
                        ->update(['confirmed_date' => date('Y-m-d')]);
    
                    $bill_email = Settings::where('key','=','bills_email')->value('value');
                    if($bill_email == 1){
                        $bill = ShipperBill::with(['items.product','items.uom', 'shipper', 'currency'])->findOrFail($id);
                        Mail::send(new Confirmed(
                            $request->only('to', 'bcc','subject', 'message'),
                            'bill', $bill
                        ));
                    }
    
                    $purchase_number = ShipperBill::where('id','=',$id)->value('number');
                    $purchase_user = ShipperBill::where('id','=',$id)->value('user_id');
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
                        $notification->link = 'shipper_bills/';
                        $notification->document_id = $id;
                        $notification->date = now();
                        $notification->created_by = $username;
                        $notification->status = 'user';
                        counter()->increment('notifications');
                        $notification->save();
    
                    }
    
                    $model->status_id = 6;
                    break;
                
                case (ShipperBill::PAID) :
                    // must be draft
                    abort_if(!in_array($model->status_id, [
                        ShipperBill::DRAFT
                    ]), 404);
    
                    $model->status_id = ShipperBill::PAID;
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
            if ($user->is_Shippers_Bills_delete == 0 && $user->is_admin != 1){
                    return response()->json(['error' => 'Forbidden.'], 403);
            }else{
            $model = ShipperBill::findOrFail($id);
    
            // check whether this particular bill belongs to
            $shipperPayments = $model->shipperPayments()->count();
    
            // if yes provide warning
    
            if($shipperPayments || !$model->is_editable) {
                return api([
                    'message' => 'Delete all the bill relations first',
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
    