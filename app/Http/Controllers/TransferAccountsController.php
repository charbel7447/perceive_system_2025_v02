<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransferAccount;
use App\Account;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\AccountItems;

class TransferAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_transferaccounts_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => TransferAccount::with('currency','from_account','to_account')->search()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->is_transferaccounts_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $usdbalance = Account::where('currency_id','=',1)->value('balance');
        $lbpbalance = Account::where('currency_id','=',2)->value('balance');
        $form = [
            'number' => counter()->next('transfer_accounts'),
            'exchangerate'=> $exchange,
            'usdbalance'=> $usdbalance,
            'lbpbalance'=> $lbpbalance,
            'transfer_date' =>date('Y-m-d'),
        ];

            $form = array_merge($form, currency()->defaultToArray());
            return api([
                'form' => $form
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_transferaccounts_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'from_account_id' => 'required',
                'to_account_id' => 'required',
                'amount' => 'required',
                'exchangerate' => 'required',
                'number' => 'nullable',
                'transfer_date' => 'required',
            ]);

            $model = new TransferAccount();
            $model->fill($request->all());
            $model->number = counter()->next('transfer_accounts');
            counter()->increment('transfer_accounts');

            if($request->from_account_id == 1 && $request->to_account_id == 2){
                $balanceUSD=  DB::table('accounts')->where('currency_id','=','1')->value('balance');
                $updateAccountUSD = DB::table('accounts')
                                    ->where('currency_id','=','1')
                                    ->update(['balance' => $balanceUSD - $request->amount ]);

                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
                $account_item->amount = $balanceUSD - $request->amount;
                $account_item->document = 'transfer_accounts';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();

                $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
                $updateAccountLBP = DB::table('accounts')
                                    ->where('currency_id','=','2')
                                    ->update(['balance' => $balanceLBP + ($request->amount * $request->exchangerate) ]);

                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
                $account_item->amount = $balanceLBP + ($request->amount * $request->exchangerate);
                $account_item->document = 'transfer_accounts';
                $account_item->type = 'plus';
                $account_item->date = date('Y');
                $account_item->save();
            }

            if($request->from_account_id == 2 && $request->to_account_id == 1){
                $balanceLBP=  DB::table('accounts')->where('currency_id','=','2')->value('balance');
                $updateAccountLBP = DB::table('accounts')
                                    ->where('currency_id','=','2')
                                    ->update(['balance' => $balanceLBP - $request->amount ]);

                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
                $account_item->amount = $balanceLBP - $request->amount;
                $account_item->document = 'transfer_accounts';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();

                $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
                $updateAccountUSD = DB::table('accounts')
                                    ->where('currency_id','=','1')
                                    ->update(['balance' => $balanceUSD + ($request->amount / $request->exchangerate) ]);

                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
                $account_item->amount = $balanceUSD + ($request->amount / $request->exchangerate);
                $account_item->document = 'transfer_accounts';
                $account_item->type = 'plus';
                $account_item->date = date('Y');
                $account_item->save();
            }
            if($request->from_account_id > 2 || $request->to_account_id > 2){
                return response()->json(['error' => 'Forbidden.'], 403);
            }

            $model->save();
            return api([
                'saved' => true,
                'id' => $model->id
            ]);
        }
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
        if ($user->is_transferaccounts_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $account = TransferAccount::with('currency','from_account','to_account')->findOrFail($id);
        return api([
            'data' => $account,
        ]);
        }
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
