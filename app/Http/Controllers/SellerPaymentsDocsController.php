<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SellerPaymentDocs\SellerPaymentDocs;
use App\SellerPaymentDocs\Item;
use App\SellerPayment\SellerPayment;
use App\Sellers\Sellers;
use DB;
use App\SellerPaymentDocs\SellerPaymentDocs as DocsItems;
use App\SellerPaymentDocs\Item as SellerPaymentDocsItem;

use Auth;
use Exception;
use App\PaymentOptionsItem;
use App\PaymentOptions;

class SellerPaymentsDocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_sellerpayments_view == 0 && $user->is_admin != 1){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        return api([
            'data' => SellerPaymentDocs::with(['seller', 'currency'])->orderby('created_at','desc')->search()
        ]);}
    }

    public function showPayments($id)
    {

         $model = SellerPaymentDocs::where('seller_id','=',$id)
             ->orderBy('created_at', 'desc')
             ->paginate(5);

      //   $model = \App\SalesOrder\SalesOrder::where('seller_id','=',$id)
       //  ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = [
            'name' => '',
            'title' => null,
            'commission' => 0,
            'commission_balance' => 0,
            'currency_id' => 1,
            'email_verified' => 1,
            'number' => counter()->next('seller_payments_docs'),
        ];

        return api([
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new SellerPaymentDocs;
        $request->validate([
            'seller.seller_payments' => 'required|array|min:1',
            'seller.seller_payments.*.amount_received' => ['required', 'numeric', 'min:0']
        ]);


        DB::table('test2')->where('id','=',1)->update(['text' => $request['seller']['seller_payments']]);

        $items = collect($request['seller']['seller_payments'])->map(function($item) {
            if($item['amount_received'] > 0) {
                return $item;
            }
        })->reject(function($item) {
            return is_null($item);
        });

        DB::table('test2')->where('id','=',2)->update(['text' => $items]);

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
        $model ->seller_id = $request->seller_id;
        // $model ->client_id = $request->client_id;
        $model ->user_id = Auth::user()->id;
        $model->date = date('Y-m-d');
        $model->year_date = date('Y');
        $model->payment_mode = $request->payment_mode;
        $model->payment_option_id = $request->payment_option_id;
        $model->payment_reference = $request->payment_reference;
        $model->document = $request->document;
        $model->note = $request->note;
        $model->status_id = '2';
        $model->number = counter()->next('seller_payments_docs');
        counter()->increment('seller_payments_docs');
     
        $model->currency_id = '1';
        $model->note = $request->note;
        $model->payment_mode = $request->payment_mode;

        DB::table('test2')->where('id','=',3)->update(['text' => $model->seller_id]);

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
            $total_earning = $seller->commission_balance - $total;
            $seller->commission_balance = $seller->commission_balance - $total;
            $seller->save();

            return $model;
        });

    
        $payment_option_balance = PaymentOptions::where('id','=',$request->payment_option_id)->value('balance');
        DB::table('payment_options')
        ->where('id', $request->payment_option_id)
        ->update(['balance' => $payment_option_balance - $items->sum('amount_received')]);
        $payment_items = new PaymentOptionsItem;
        $payment_items->payment_options_id = $request->payment_option_id;
        $payment_items->payment = $items->sum('amount_received');
        $payment_items->user_id = auth()->id();
        $payment_items->created_by = $username;
        $payment_items->date = date('Y-m-d');
        $payment_items->time = now();
        $payment_items->year_date = date('Y');
        $payment_items->document = 'seller_payment';
        $payment_items->document_id = $model->id;
        $payment_items->document_number = $model->number;
        $payment_items->client_id = $model->seller_id;
        $payment_items->client_name =  DB::table('admins')->where('id', $model->seller_id)->value('name');
        $payment_items->save();
        
        return api([
            'saved' => true,
            'id' => $model->id
        ]);

      
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
        if ($user->is_sellers_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $data = SellerPaymentDocs::with(['seller', 'currency','items','items.sales_order'])->findOrFail($id);
            return api([
                'data' => $data
            ]);
        }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_sellers_view == 0 && $user->is_admin != 1){
            //   dd($user);
           //    dd('You dont have Permission');
        return response()->json(['error' => 'Forbidden.'], 403);
            }else{
        $data = SellerPaymentDocs::with(['seller', 'currency','items','items.sales_order'])->findOrFail($id);
        return pdf('docs.seller_payment_docs', $data);
    }}

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
        // throw new Exception($id);
        $items = SellerPaymentDocsItem::where('client_payment_id','=',$id)->get();
     
        foreach($items as $item){

           
            SellerPaymentDocs::where('id','=',$id)->delete();


            $amount_received = SellerPayment::where('id','=',$item->seller_payment_id)->value('amount_received');
            $amount_pending = SellerPayment::where('id','=',$item->seller_payment_id)->value('amount_pending');
            SellerPayment::where('id','=',$item->seller_payment_id)->update(['amount_received' => $amount_received - $item->amount_received]);
            SellerPayment::where('id','=',$item->seller_payment_id)->update(['amount_pending' => $amount_pending + $item->amount_received]);
            SellerPaymentDocsItem::where('client_payment_id','=',$item->client_payment_id)->delete();
        }

        return api([
            'deleted' => true
        ]);

        // throw new Exception($model);
    }
}
