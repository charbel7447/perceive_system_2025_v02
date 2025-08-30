<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorStatement\VendorStatement;
use App\VendorStatement\Item;
use App\Vendor;
use App\Bill\Bill;
use App\Expense\Expense;
use App\VendorPayment\VendorPayment;
use Auth;
use DB;
use PDF;
use App\User;

class VendorStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_vendorsoa_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Vendor::search()
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
        $user = auth()->user();
        if ($user->is_vendorsoa_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Vendor::with(['currency'])->findOrFail($id)
            ]);
        }
    }

    public function report($id)
    {
         return ('statement.report');
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
        $user = auth()->user();
        if ($user->is_vendorsoa_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'person' => 'nullable|max:255',
            'company' => 'nullable|max:255',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d'
        ]);

        VendorStatement::truncate();
        Item::truncate();
        // $inputs = $request->all();
        $statement = new VendorStatement;
        $statement ->vendor_id = $id;
        $statement ->person = $request->input('person');
        $statement ->company = $request->input('company');
        $statement ->date = $request->input('date');
        $statement ->due_date = $request->input('due_date');
        $username = Auth::user()->name;
        $statement ->created_by = $username;
        // $statement = Statement::Create($inputs);
        $statement->save();   

        //Invoices
        $bills = Bill::where('date','>=',$request->input('date'))
        ->where('date','<=',$request->input('due_date'))->where('vendor_id','=',$id)->where('status_id','>=','1')->get();
    
        foreach ($bills as $bill) 
        {
            $statementIN = new Item;
            $statementIN ->statement_id = $statement->id;
            $statementIN ->vendor_id = $id;
            $statementIN ->reference_id = $bill->id;
            $statementIN ->amount_applied = $bill->total;
            $statementIN ->reference_date = $bill->date;
            $statementIN ->type = 'bill';
            $statementIN ->reference_number = $bill->number;
            $statementIN->save(); 
        }


        //VendorPayment Payments
        $payments = VendorPayment::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('vendor_id','=',$id)->get();
    
        foreach ($payments as $payment)
        {
            $statementPay = new Item;
            $statementPay ->statement_id = $statement->id;
            $statementPay ->vendor_id = $id;
            $statementPay ->reference_id = $payment->id;
            $statementPay ->amount_applied = $payment->amount_paid;
            $statementPay ->reference_date = $payment->payment_date;
            $statementPay ->type = 'vendorpayment';
            $statementPay ->reference_number = $payment->number;
            $statementPay->save(); 
        }

           //paymenttVoucher
        $payments1 = \App\PaymentVoucher\PaymentVoucher::where('date','>=',$request->input('date'))
        ->where('date','<=',$request->input('due_date'))->where('vendor_id','=',$id)->get();
    
        foreach ($payments1 as $Pay1)
        {
            $statementPay = new Item;
            $statementPay ->statement_id = $statement->id;
            $statementPay ->vendor_id = $id;
            $statementPay ->reference_id = $Pay1->id;
            $statementPay ->amount_applied = $Pay1->total;
            $statementPay ->amount_applied_vat = $Pay1->total_debit_vat;
            $statementPay ->reference_date = $Pay1->date;
            $statementPay ->type = 'paymentvoucher';
            $statementPay ->reference_number = $Pay1->number;
            $statementPay->save(); 
        }


        //ad Payments
        $excpenses = Expense::where('payment_date','>=',$request->input('date'))
        ->where('payment_date','<=',$request->input('due_date'))->where('vendor_id','=',$id)->get();
    
        foreach ($excpenses as $excpense)
        {
            $statementAd = new Item;
            $statementAd ->statement_id = $statement->id;
            $statementAd ->vendor_id = $id;
            $statementAd ->reference_id = $excpense->id;
            $statementAd ->amount_applied = $excpense->total_usd_1;
            $statementAd ->amount_applied_vat = $excpense->total_vat_1;
            $statementAd ->reference_date = $excpense->payment_date;
            $statementAd ->type = 'expenses';
            $statementAd ->reference_number = $excpense->number;
            $statementAd->save(); 
        }


        return api(['saved' => true]);
    }

    }


    public function pdf($id)
    {
        $user = auth()->user();
        if ($user->is_vendorsoa_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $user = auth()->user();
         $statement = VendorStatement::latest()->latest()->take(1)->get();
         $dateS = VendorStatement::select('date')->latest()->take(1)->get();
         $dateE = VendorStatement::select('due_date')->latest()->take(1)->get();
        
         $vendor = Vendor::findOrFail($id);

         foreach ($dateS as $startdate) {
                
                $StartDates = $startdate->date;

            foreach ($dateE as $enddate) {

                $EndDates = $enddate->due_date;

         $bills = Bill::where('date','>=',$StartDates)
        ->where('date','<=',$EndDates)->where('vendor_id','=',$id)->where('status_id','>=','1')->get();

        //  $invoicesItems = Item::where('created_at','>=',$StartDates)
        // ->where('created_at','<=',$EndDates)->get();

         $payment = VendorPayment::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('vendor_id','=',$id)->get();

         $excpenses = Expense::where('payment_date','>=',$StartDates)
        ->where('payment_date','<=',$EndDates)->where('vendor_id','=',$id)->get();


        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
        ->setPaper('a4', 'portrait')->setWarnings(false)
        ->loadView('docs.vendor_statement', 
          compact('vendor','bills','payment','excpenses','statement','dateS','dateE','EndDates','StartDates'));

      //  return view('docs.vendor_statement', compact('vendor','bills','payment','excpenses','statement','dateS','dateE','EndDates','StartDates'));

        // return $pdf->download('vendor_statement.pdf');

        $name = VendorStatement::where('vendor_id','=',$id)->select('company')->latest()->take(1)->get();
        foreach($name as $person){
            return $pdf->download(now().'--'.$person->company.'--Statement.pdf');
        }
        // return pdf('docs.statement', $data);
      //'id', '=', $request->get('product')
        }
    }

    }}

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
