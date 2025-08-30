<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payroll;
use App\Employee;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\AccountItems;

class PayrollsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_payroll_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => Payroll::with(['employee', 'currency'])->search()
        ]);
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_payroll_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'employee_id' => 'sometimes|required|integer|exists:employees,id'
        ]);
        $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
        $form = [
            'employee_id' => null,
            'employee' => null,
            'number' => counter()->next('payroll'),
            'payment_date' => date('Y-m-d'),
            'amount_paid' => 0,
            'amount_paid_lbp' => 0,
            'description' => null,
            'exchangerate'=> $exchange
        ];

        if($request->has('employee_id')) {
            $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            $employee = Employee::with(['currency'])->findOrFail($request->employee_id);

            array_set($form, 'salary', $employee->salary);
            array_set($form, 'employee_id', $employee->id);
            array_set($form, 'employee', $employee);
            array_set($form, 'currency_id', $employee->currency->id);
            array_set($form, 'currency', $employee->currency);
            array_set($form, 'exchangerate', $exchange);

        } else {
            $form = array_merge($form, currency()->defaultToArray());
        }

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
        if ($user->is_payroll_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'employee_id' => 'required',
            'currency_id' => 'required|integer|exists:currencies,id',
            'payment_date' => 'required|date_format:Y-m-d',
            'amount_paid' => 'nullable',
            'amount_paid_lbp' => 'nullable',
            'description' => 'nullable|max:2000',
            'salary' => 'nullable',
            'exchangerate' => 'nullable'
        ]);

        $model = new Payroll();
        $model->fill($request->all());
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->user_id = auth()->id();

        $model = DB::transaction(function() use ($model, $request) {

            $model->number = counter()->next('payroll');

            $model->save();

            $balanceLBP =  DB::table('accounts')->where('currency_id','=','2')->value('balance');
            $updateAccountLBP = DB::table('accounts')
                                ->where('currency_id','=','2')
                                ->update(['balance' => $balanceLBP - $model->amount_paid_lbp ]);

            $balanceUSD =  DB::table('accounts')->where('currency_id','=','1')->value('balance');
            $updateAccountUSD = DB::table('accounts')
                                ->where('currency_id','=','1')
                                ->update(['balance' => $balanceUSD - $model->amount_paid ]);

                                
                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','2')->value('id');
                $account_item->amount = $balanceLBP - $model->amount_paid_lbp;
                $account_item->document = 'payroll';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();

                $account_item = new AccountItems();
                $account_item->account_id = DB::table('accounts')->where('currency_id','=','1')->value('id');
                $account_item->amount = $balanceUSD - $model->amount_paid;
                $account_item->document = 'payroll';
                $account_item->type = 'negative';
                $account_item->date = date('Y');
                $account_item->save();

            counter()->increment('payroll');

            return $model;
        });

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
        if ($user->is_payroll_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        return api([
            'data' => Payroll::with(['employee', 'currency'])->findOrFail($id)
        ]);
    }
    }

    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_payroll_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = Payroll::with(['employee', 'currency'])->findOrFail($id);
        return pdf('docs.payroll', $data);
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
