<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposit;
use App\Account;
use App\Employee;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\AccountItems;

class DepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_deposit_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Deposit::with('currency','employee','to_account')->search()
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
        if ($user->is_deposit_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $usdbalance = Account::where('currency_id','=',1)->value('balance');
        $lbpbalance = Account::where('currency_id','=',2)->value('balance');
        $form = [
            'number' => counter()->next('deposit'),
            'exchangerate'=> $exchange,
            'usdbalance'=> $usdbalance,
            'lbpbalance'=> $lbpbalance,
            'deposit_date' =>date('Y-m-d'),
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
        if ($user->is_deposit_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'employee_id' => 'required',
            'to_account_id' => 'required',
            'amount' => 'required',
            'exchangerate' => 'required',
            'number' => 'nullable',
            'deposit_date' => 'required',
        ]);

        $model = new Deposit();
        $model->fill($request->all());
        $model->number = counter()->next('deposit');
        counter()->increment('deposit');

        if($request->to_account_id == 2){
            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP + ($request->amount) ]);
                                // ->update(['balance' => $balanceLBP + ($request->amount / $request->exchangerate) ]);

            // $account_item = new AccountItems();
            // $account_item->account_id = 2;
            // $account_item->amount = +($request->amount * $request->exchangerate);
            // $account_item->document = 'deposit account 2';
            // $account_item->type = 'positive';
            // $account_item->date = date('Y');
            // $account_item->save();

            $account_item = new AccountItems();
            $account_item->account_id = 2;
            // $account_item->amount = +($request->amount / $request->exchangerate);
            $account_item->amount = +($request->amount);
            $account_item->document = 'deposit account 1';
            $account_item->type = 'negative';
            $account_item->date = date('Y');
            $account_item->save();

        }

        if($request->to_account_id == 1){
            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                // ->update(['balance' => $balanceUSD + ($request->amount / $request->exchangerate) ]);
                                ->update(['balance' => $balanceUSD + ($request->amount) ]);

            $account_item = new AccountItems();
            $account_item->account_id = 1;
            $account_item->amount = +($request->amount);
            $account_item->document = 'deposit account 1';
            $account_item->type = 'negative';
            $account_item->date = date('Y');
            $account_item->save();

            // $account_item = new AccountItems();
            // $account_item->account_id = 2;
            // $account_item->amount = -($request->amount);
            // $account_item->document = 'deposit account 2';
            // $account_item->type = 'positive';
            // $account_item->date = date('Y');
            // $account_item->save();
        }
        if($request->employee_id == null){
            return response()->json(['error' => 'Forbidden.'], 403);
        }

        $employeeDeposit =  DB::table('employees')->where('id','=',$request->employee_id)->value('deposit');
        $employeeCurrency =  DB::table('employees')->where('id','=',$request->employee_id)->value('currency_id');
        if($employeeCurrency == 1 && $request->to_account_id == 1){
            DB::table('employees')
            ->where('id','=',$request->employee_id)
            ->update(['deposit' => $employeeDeposit + ($request->amount) ]);
        }
        if($employeeCurrency == 1 && $request->to_account_id == 2){
            DB::table('employees')
            ->where('id','=',$request->employee_id)
            ->update(['deposit' => $employeeDeposit + ($request->amount / $request->exchangerate) ]);
        }
        if($employeeCurrency == 2 && $request->to_account_id == 1){
            DB::table('employees')
            ->where('id','=',$request->employee_id)
            ->update(['deposit' => $employeeDeposit + ($request->amount * $request->exchangerate) ]);
        }
        if($employeeCurrency == 2 && $request->to_account_id == 2){
            DB::table('employees')
            ->where('id','=',$request->employee_id)
            ->update(['deposit' => $employeeDeposit + ($request->amount) ]);
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
        if ($user->is_deposit_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $account = Deposit::with('currency','employee','to_account')->findOrFail($id);
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
