<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ReceiveOrder\ReceiveOrder;
use Exception;
use DB;
use Auth;

use PDF;
use App\Currency;

use App\ReturnDeposit;
use App\Deposit;
use App\ExchangeRate\ExchangeRate;
use App\Payroll;
use App\User;
use App\Employee;

use App\EmployeeReport\EmployeeReport;
use App\EmployeeReport\Item as ReportItem;

class EmployeeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->is_employees_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $form = array_merge([
                'user_id' => '',
                'employee_id' => '',
                'created_by' => '',
                'from_date' => '',
                'to_date' => ''
            
            ]);

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
        if ($user->is_employees_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'employee_id' => 'required',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new EmployeeReport;
          $report ->user_id = $username;
          $report ->employee_id = $request->input('employee_id');
          if($request->input('from_date')){
            $report ->from_date = $request->input('from_date').' 01:00:00';
          }
          if($request->input('to_date')){
            $report ->to_date = $request->input('to_date').' 00:00:00';
          }
          
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save(); 

          
        //Payroll
        $payrolls = Payroll::where('payment_date','>=',$request->input('from_date').' 01:00:00')
                            ->where('payment_date','<=',$request->input('to_date').' 01:00:00')
                            ->where('employee_id','=',$request->input('employee_id'))
                            ->get();
    
        foreach ($payrolls as $payroll) 
        {
            $reportItem = new ReportItem;
            $reportItem ->report_id = $report->id;
            $reportItem ->employee_id = $request->input('employee_id');
            $reportItem ->payroll_id = $payroll->id;
            $reportItem ->type = 'payroll';
            $reportItem ->payroll_date = $payroll->payment_date;
            $reportItem ->currency_id = $payroll->currency_id;
            $reportItem ->number = $payroll->number;
            $reportItem ->amount_paid = $payroll->amount_paid;
            $reportItem ->amount_paid_lbp = $payroll->amount_paid_lbp;
            $reportItem ->exchangerate = $payroll->exchangerate;
            $reportItem ->from_date = $request->input('from_date').' 01:00:00';
            $reportItem ->to_date = $request->input('to_date').' 01:00:00';
            $reportItem->save(); 
        }

        //Deposit
        $deposits = Deposit::where('deposit_date','>=',$request->input('from_date').' 01:00:00')
                            ->where('deposit_date','<=',$request->input('to_date').' 01:00:00')
                            ->where('employee_id','=',$request->input('employee_id'))
                            ->get();
    
        foreach ($deposits as $deposit) 
        {
            $reportItem = new ReportItem;
            $reportItem ->report_id = $report->id;
            $reportItem ->employee_id = $request->input('employee_id');
            $reportItem ->deposit_id = $deposit->id;
            $reportItem ->type = 'deposit';
            $reportItem ->deposit_date = $deposit->deposit_date;
            $reportItem ->currency_id = $deposit->currency_id;
            $reportItem ->number = $deposit->number;
            $reportItem ->deposit_amount = $deposit->amount;
            $reportItem ->to_account_id = $deposit->to_account_id;
            $reportItem ->exchangerate = $deposit->exchangerate;
            $reportItem ->from_date = $request->input('from_date').' 01:00:00';
            $reportItem ->to_date = $request->input('to_date').' 01:00:00';
            $reportItem->save(); 
        }


        //Retun Deposit
        $return_deposits = ReturnDeposit::where('return_date','>=',$request->input('from_date').' 01:00:00')
                            ->where('return_date','<=',$request->input('to_date').' 01:00:00')
                            ->where('employee_id','=',$request->input('employee_id'))
                            ->get();
    
        foreach ($return_deposits as $return_deposit) 
        {
            $reportItem = new ReportItem;
            $reportItem ->report_id = $report->id;
            $reportItem ->employee_id = $request->input('employee_id');
            $reportItem ->return_deposit_id = $return_deposit->id;
            $reportItem ->type = 'return_deposit';
            $reportItem ->return_deposit_date = $return_deposit->return_date;
            $reportItem ->currency_id = $deposit->currency_id;
            $reportItem ->number = $return_deposit->number;
            $reportItem ->return_deposit_amount = $return_deposit->amount;
            $reportItem ->from_account_id = $return_deposit->from_account_id;
            $reportItem ->exchangerate = $return_deposit->exchangerate;
            $reportItem ->from_date = $request->input('from_date').' 01:00:00';
            $reportItem ->to_date = $request->input('to_date').' 01:00:00';
            $reportItem->save(); 
        }

        return api([
            'saved' => true,
            'id' => $report->id
        ]);
    }
    }

    public function pdf()
    {
        $user = auth()->user();
        if ($user->is_employees_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $ReportId1 = EmployeeReport::latest()->take(1)->get();
            foreach ($ReportId1 as $RequestID){
                $reports = ReportItem::with(['employee','to_account','from_account'])
                ->where('report_id','=',$RequestID->id)
                ->get();
                $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.employee_report',compact('reports'));
            return $pdf->download(now().'employee_report.pdf');

            
                // return view('docs.purchaser',compact('reports'));
            }
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
