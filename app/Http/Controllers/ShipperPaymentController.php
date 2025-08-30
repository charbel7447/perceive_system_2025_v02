<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShipperPayment\ShipperPayment;
use App\Bill\Bill;
use App\Shipper;
use DB;
use Auth;
use App\ShipperBill\ShipperBill;

class ShipperPaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_Shippers_Payments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ShipperPayment::with(['shipper', 'currency'])->search()
            ]);
        }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_Shippers_Payments_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'shipper_id' => 'sometimes|required|integer|exists:shippers,id'
        ]);

        $form = [
            'shipper_id' => null,
            'shipper' => null,
            'number' => counter()->next('shipper_payment'),
            'payment_reference' => null,
            'payment_date' => date('Y-m-d'),
            'payment_mode' => 'cash',
            'amount_paid' => 0,
            'amount_paid_lbp'=> 0,
            'items' => []
        ];

        if($request->has('shipper_id')) {
            $shipper = Shipper::with(['currency'])->findOrFail($request->shipper_id);

            array_set($form, 'shipper_id', $shipper->id);
            array_set($form, 'shipper', $shipper);
            array_set($form, 'currency_id', $shipper->currency->id);
            array_set($form, 'currency', $shipper->currency);

            // get all draft and partialy paid bills
            $bills = $shipper->shipperbills()->whereIn('status_id', [1,2])
                ->get([
                    'amount_paid', 'total', 'date', 'status_id', 'due_date',
                    'number', 'id as shipper_bill_id',
                    DB::raw('0 as amount_applied'),
                    DB::raw('0 as amount_applied_lbp'),
                    DB::raw('1 as amount_applied_lbp_rate'),
                    DB::raw('1 as amount_applied_vat_rate'),
                    DB::raw('0 as amount_applied_vat')
                ]);

            if($bills->count()) {
                array_set($form, 'items', $bills->toArray());
            }
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
        if ($user->is_Shippers_Payments_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'shipper_id' => 'required|integer|exists:shippers,id',
            'currency_id' => 'required|integer|exists:currencies,id',
            'payment_mode' => 'required|in:cheque,cash,bank_transfer',
            'payment_reference' => 'required_unless:payment_mode,cash',
            'document' => 'required_if:payment_mode,cheque|image|max:2048',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_paid' => 'nullable',
            'amount_paid_lbp'=> 'nullable',
            'items' => 'required|array|min:1',
            'items.*.shipper_bill_id' => 'required|integer',
            // 'items.*.amount_applied' => ['required', 'numeric', 'min:0', 'bill_balance:items.*.bill_id']
            'items.*.amount_applied' => ['nullable'],
            'items.*.amount_applied_lbp' => ['nullable'],
            'items.*.amount_applied_lbp_rate' => ['nullable'],
        
        ]);

        $model = new ShipperPayment();
        $model->fill($request->except('items'));
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
        $model->status_id = ShipperPayment::PAID;

        $items = collect($request->items)->map(function($item) {
            $total_amount_applied = (round($item['amount_applied'] + ( $item['amount_applied_lbp'] * $item['amount_applied_lbp_rate']),2));
            
            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP - ($item['amount_applied_lbp']) ]);

            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                ->update(['balance' => $balanceUSD - ($item['amount_applied']) ]);

            if( $total_amount_applied > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        // throw error if amount_applied is invalid

        if($items->sum('amount_applied') != $request->amount_paid) {
            return api([
                'errors' => [
                    'amount_paid' => ['Amount paid does not match amount applied']
                ]
            ], 422);
        }

        $lbpSum = $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate');
      
        $LbpVat = $lbpSum;
        if(round($LbpVat,2) != $request->amount_paid_lbp ) {
            return api([
                'errors' => [
                    'amount_paid_lbp' => ['Amount paid does not match amount applied try to receive one bill in case you have two payments in second currency'],
                ]
            ], 422);
        }

        $model->amount_paid = (round($items->sum('amount_applied') + ( $items->sum('amount_applied_lbp') / $items->sum('amount_applied_lbp_rate')),2));
        $model->amount_paid_usd = (round($items->sum('amount_applied'),2));


        $model = DB::transaction(function() use ($model, $items) {

            $model->number = counter()->next('shipper_payment');

            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update bills

            $model->items->each(function($item) {
                $bill = $item->shipper_bill;
                $amount = $bill->amount_paid + (round($item->amount_applied + ($item->amount_applied_lbp / $item->amount_applied_lbp_rate) + ($item->amount_applied_vat / $item->amount_applied_vat_rate),2));
                // $amount = $bill->amount_paid + $item->amount_applied;

                if($amount > $bill->total) {
                    throw new Exception('Amount overflow');
                }

                $bill->amount_paid = $amount;
                $bill->status_id = ShipperBill::PARTIALLY_PAID;

                if($bill->amount_paid == $bill->total) {
                    $bill->status_id = ShipperBill::PAID;
                }

                $bill->save();
            });

            //  2. update vendor total_expense
            $vendor = $model->shipper;
            $vendor->total_expense = $vendor->total_expense + $model->amount_paid;
            $vendor->save();

            counter()->increment('shipper_payment');

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
        if ($user->is_Shippers_Payments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ShipperPayment::with(['items.shipper_bill', 'shipper', 'currency'])->findOrFail($id)
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_Shippers_Payments_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = ShipperPayment::with(['items.shipper_bill', 'shipper', 'currency'])->findOrFail($id);
            return pdf('docs.shipper_payment', $data);
        }
    }
}
