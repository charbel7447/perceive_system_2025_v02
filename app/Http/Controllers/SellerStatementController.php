<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sellers\Sellers;
use App\Invoice\Invoice;
use DB;
use Auth;
use App\SellerStatement\SellerStatement;
use App\SellerStatement\Item;
use App\SellerPayment\SellerPayment;
use App\SalesOrder\SalesOrder;
use App\SellerPaymentDocs\SellerPaymentDocs;
use App\SellerPaymentDocs\Item as DocsItem;
use PDF;

class SellerStatementController extends Controller
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
                'data' => Sellers::where('role','=','seller')->search()
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
                'form' => Sellers::where('role','=','seller')->findOrFail($id)
            ]);
        }
    }

    public function report($id)
    {
         return ('seller_statement.report');
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
                'name' => 'nullable|max:255',
                'phone' => 'nullable|max:255',
                'commission' => 'nullable|max:255',
                'commission_balance' => 'nullable|max:255',
                'phone' => 'nullable|max:255',
                'date' => 'required|date_format:Y-m-d',
                'due_date' => 'required|date_format:Y-m-d'
            ]);

            
            // $inputs = $request->all();
            $statement = new SellerStatement;
            $statement ->seller_id = $id;
            $statement ->name = $request->input('name');
            $statement ->phone = $request->input('phone');
            $statement ->commission = $request->input('commission');
            $statement ->commission_balance = $request->input('commission_balance');
            $statement ->date = $request->input('date');
            $statement ->due_date = $request->input('due_date');
            $username = Auth::user()->name;
            $statement ->created_by = $username;
            // $statement = Statement::Create($inputs);
            $statement->save();   

            //Invoices
            $seller_payments = SellerPayment::where('date','>=',$request->input('date'))
            ->where('date','<=',$request->input('due_date'))->where('seller_id','=',$id)->where('status_id','>=','1')->get();
        
            foreach ($seller_payments as $seller_payment) 
            {
                $statementIN = new Item;
                $statementIN ->statement_id = $statement->id;
                $statementIN ->seller_id = $id;
                $statementIN ->reference_id = $seller_payment->id;
                $statementIN ->number = $seller_payment->number;
                $statementIN ->seller_payment_id = $seller_payment->client_payment_id;
                $statementIN ->order_id = $seller_payment->order_id;
                $seller_payment_number = SalesOrder::where('id','=',$seller_payment->order_id)->value('number');
                $statementIN ->reference_number = $seller_payment_number;
                $statementIN ->reference_date = $seller_payment->date;
                $statementIN ->type = 'seller_payment';
                $statementIN ->amount_applied = $seller_payment->total_amount;
                $statementIN ->amount_received = $seller_payment->amount_received;
                $statementIN ->amount_pending = $seller_payment->amount_pending;
                $statementIN->save(); 
            }


            //VendorPayment Payments
            $payments = SellerPaymentDocs::where('payment_date','>=',$request->input('date'))
            ->where('payment_date','<=',$request->input('due_date'))->where('seller_id','=',$id)->get();
        
            foreach ($payments as $payment)
            {
                $statementPay = new Item;
                $statementPay ->statement_id = $statement->id;
                $statementPay ->seller_id = $id;
                $statementPay ->reference_id = $payment->id;
                $statementPay ->amount_applied = $payment->total_amount_received;
                $statementPay ->reference_date = $payment->payment_date;
                $statementPay ->seller_payment_id = $statement->client_payment_id;
                $statementPay ->type = 'seller_payment_docs';
                $statementPay ->reference_number = $payment->number;
                $statementPay->save(); 
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
            $statement = SellerStatement::latest()->latest()->take(1)->get();
            $dateS = SellerStatement::select('date')->latest()->take(1)->get();
            $dateE = SellerStatement::select('due_date')->latest()->take(1)->get();
            
            $seller = Sellers::findOrFail($id);

            foreach ($dateS as $startdate) {
                    
                    $StartDates = $startdate->date;

                foreach ($dateE as $enddate) {

                    $EndDates = $enddate->due_date;

            $seller_payments = SellerPayment::where('date','>=',$StartDates)
            ->where('date','<=',$EndDates)->where('seller_id','=',$id)->where('status_id','>=','1')->get();

            $payment = SellerPaymentDocs::where('payment_date','>=',$StartDates)
            ->where('payment_date','<=',$EndDates)->where('seller_id','=',$id)->get();



            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])
            ->setPaper('a4', 'portrait')->setWarnings(false)
            ->loadView('docs.seller_statement', 
            compact('seller','seller_payments','payment','statement','dateS','dateE','EndDates','StartDates'));

         // return view('docs.seller_statement', compact('seller','seller_payments','payment','statement','dateS','dateE','EndDates','StartDates'));

            // return $pdf->download('vendor_statement.pdf');

            $name = SellerStatement::where('seller_id','=',$id)->select('name')->latest()->take(1)->get();
            foreach($name as $name){
                return $pdf->download(now().'--'.$name->name.'--Statement.pdf');
            }
            // return pdf('docs.statement', $data);
        //'id', '=', $request->get('product')
            }
        }
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
