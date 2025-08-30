<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sellers\Sellers;
use App\Invoice\Invoice;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;


class SellersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_sellers_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Sellers::where('role','=','seller')->search()
            ]);
        }
    }

    public function search()
    {
        // /with('salesOrders')->
        $results = Sellers::when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%')
                    ->orWhere('username', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get()
            ->when(request('with') == 'seller_payments', function($sellers) {
                return $sellers->map(function($seller) {
                    $seller->seller_payments = $seller->seller_payments()
                    // ->whereIn('status_id', [1, 2])
                    // ->where('seller_payments.amount_pending','>',0)
                    ->join('invoices','seller_payments.order_id','=','invoices.id')
                        ->get([
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
                    return $seller;
                });
            });

        return api([
            'results' => $results
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
        $request->validate([
            'name' => 'nullable|max:255',
            'commission' => 'nullable|max:255',
            'email' => 'nullable|max:255',
            'commission_balance' => 'nullable|max:255',
            'username' => 'nullable|max:255',
            'phone' => 'nullable|max:255',
            'email_verified' => 'nullable|max:255',
            'password' => 'required|max:255',
        ]);


        $model = new Sellers;
    //    $model->id = $get_seller_id_latest+1;
        $model->fill($request->all());
        $model->password = Hash::make($request->password);
        $model->role = 'seller';
        $model->save();

        
       

        

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
            $seller = Sellers::with(['currency'])->findOrFail($id);
            $stats = [
                'commission' => $seller->commission,
                'commission_balance' => $seller->commission_balance,
                'total_orders_commission' => $seller->salesOrders()->where('status_id','>',1)->sum(DB::raw('seller_commission')),
                'total_orders_count' => $seller->salesOrders()->where('status_id','>',1)->count('id'),
            ];
            return api([
                'data' => $seller,
                'stats' => $stats
            ]);
        }
    }

    public function showSalesOrdersSellers($id)
    {
        $client = Sellers::findOrFail($id);

         $model = $client->salesOrders()
             ->with(['currency'])
             ->orderBy('created_at', 'desc')
             ->paginate(5);

      //   $model = \App\SalesOrder\SalesOrder::where('seller_id','=',$id)
       //  ->paginate(5);

        return api([
            'model' => $model
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_sellers_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Sellers::with(['currency'])->findOrFail($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->is_sellers_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Sellers::findOrFail($id);

            $request->validate([
                'name' => 'nullable|max:255',
                'commission' => 'nullable|max:255',
                'email' => 'nullable|max:255',
                'commission_balance' => 'nullable|max:255',
                'username' => 'nullable|max:255',
                'phone' => 'nullable|max:255',
                'email_verified' => 'nullable|max:255',
            ]);

            $model->fill($request->all());

            $model->name = $request->name;
            $model->commission = $request->commission;
            $model->email = $request->email;
            $model->commission_balance = $request->commission_balance;
            $model->username = $request->username;
            $model->phone = $request->phone;
            $model->currency_id = $request->currency_id;
            $model->role = 'seller';
            // if(Hash::check( $model->password , $request->password ) ){
             if($model->password == $request->password){  
                // $model->password = 'same';
            }else{
                $model->password = Hash::make($request->password);
            }
            $model->save();


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
        //
    }
}
