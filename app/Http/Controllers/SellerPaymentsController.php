<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SellerPayment\SellerPayment;
use App\Sellers\Sellers;
use DB;
use App\SellerPaymentDocs\SellerPaymentDocs;
use App\SellerPaymentDocs\SellerPaymentDocs as DocsItems;
use Auth;
use Exception;

class SellerPaymentsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_sellerpayments_view == 0 && $user->is_admin != 1){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => SellerPayment::with(['client', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }

    public function showPaymentApply($id)
    {
        $data = SellerPayment::with(['client', 'currency','sales_order'])
            ->join('invoices','seller_payments.order_id','=','invoices.id')
            ->whereIn('seller_payments.status_id',['1','2'])
            ->where('seller_payments.seller_id','=',$id)
            ->where('seller_payments.amount_pending','>',0);
            $data->items = $data->get([
                'seller_payments.id as seller_payment_id',
                'seller_payments.number',
                'seller_payments.order_amount',
                'seller_payments.total_amount',
                'seller_payments.amount_pending',
                'seller_payments.client_id',
                // 'seller_payments.amount_received',
                DB::raw('0 as amount_received'),
                'invoices.id as sales_order_id',
                'invoices.number as sales_order_number'
            ]);
        //  $data->items = $data->get([
        //     'total_amount', 'date', 'status_id',
        //     'number', 'order_id',
        //     DB::raw('0 as amount_applied')
        //     ]);
         
        return api([
            'data' => $data
        ]);
    }


    public function showPayments($id)
    {
        $client = Sellers::findOrFail($id);

         $model = $client->sellerpayments()
             ->with(['currency'])
             ->orderBy('created_at', 'desc')
             ->paginate(5);

            //  throw new Exception('Amount overflow');
      //   $model = \App\SalesOrder\SalesOrder::where('seller_id','=',$id)
       //  ->paginate(5);

        return api([
            'model' => $model
        ]);
    }
    

    public function applyPayments($id, Request $request)
    {
        // $model = SellerPayment::whereStatusId(SellerPayment::DRAFT)
        // ->where('seller_id','=',$id)->get();

        // throw new Exception($id);

        $model = new SellerPaymentDocs;
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.amount_received' => ['required', 'numeric', 'min:0']
        ]);

        $items = collect($request->items)->map(function($item) {
            if($item['amount_received'] > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        // throw error if amount_applied is invalid or less
        if($items->sum('amount_received') > $items->sum('amount_pending')) {
            return api([
                'errors' => [
                    'amount_received' => ['Amount received greater than pending amount']
                ]
            ], 422);
        }

        $model->total_amount_received = $items->sum('amount_received');
        $model->payment_date = date('Y-m-d');
        $model->payment_at = now();

        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model ->payment_by = $username;
        $model ->seller_id = $id;
        $model ->client_id = $request->client_id;
        $model ->user_id = Auth::user()->id;
        $model->date = date('Y-m-d');
        $model->year_date = date('Y');
        $model->payment_mode = $request->payment_mode;
        $model->payment_reference = $request->payment_reference;
        $model->document = $request->document;
        $model->note = $request->note;
        $model->status_id = '2';
        $model->number = counter()->next('seller_payments_docs');
        counter()->increment('seller_payments_docs');
     
        $model->currency_id = '1';
        $model->note = $request->note;
        $model->payment_mode = $request->payment_mode;


           // upload document if exists
           if($request->hasFile('document') && $request->file('document')->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile($request->document)) {
                $model->document = $fileName;
           }
        }
        
        $model = DB::transaction(function() use ($model, $items) {
            // $model->status_id = AdvancePayment::DRAWN;
            
            $model->storeHasMany([
                'items' => $items
            ]);

            //  1. update invoices
           

            $model->items->each(function($item) {
                $seller_payment = $item->seller_payments;
                $amount = $seller_payment->amount_pending - $item->amount_received;

                if($amount > $seller_payment->total_amount) {
                    throw new Exception('Amount overflow');
                }
                $seller_payment->amount_pending = $amount;
                $seller_payment->amount_received = $item->amount_received;
                $seller_payment->status_id = 2;

                if($seller_payment->amount_received == $seller_payment->total) {
                    $seller_payment->status_id = 3;
                }
                $seller_payment->currency_id = '1';
                 $seller_payment->save();

                    DB::table('seller_payments_docs_items')
                    ->where('id', $item->id)
                    ->update(['amount_pending' => $amount]);
 
            });

           
            //  2. update client revenue and also reduce unused credit
            $seller = $model->seller;
            $total = $items->sum('amount_received');
            $total_earning  = $seller->commission_balance - $total;
            $seller->commission_balance = $seller->commission_balance - $total;
            
            $seller->save();

            return $model;
        });

    

        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
