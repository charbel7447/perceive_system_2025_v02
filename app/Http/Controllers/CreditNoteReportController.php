<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreditNoteReport\CreditNoteReport;
use App\CreditNoteReport\Item as ReportItem;

use App\CreditNote\CreditNote;
use App\CreditNote\Item as PurchaseItem;

use DB;
use Auth;
use PDF;

use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;

use App\Excel\CreditNoteReportExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
class CreditNoteReportController extends Controller
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
        if ($user->is_creditnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'user_id' => '',
            'product_id' => '',
            'client_id' => '',
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
        if ($user->is_creditnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'client_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new CreditNoteReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->client_id = $request->input('client_id');
         
          $from_date= 0;
          $to_date = 0;
          if($request->input('from_date')){
            $report ->from_date = $request->input('from_date');
            $from_date = $request->input('from_date');
          }else{
            $report ->from_date = '1990-01-01';
            $from_date = '1990-01-01';
          }
          if($request->input('to_date')){
            $report ->to_date = $request->input('to_date');
            $to_date = $request->input('to_date');
          }else{
            $report ->to_date = '2030-01-01';
            $to_date = '2030-01-01';
          }
          
          $username = Auth::user()->name;
          $report ->created_by = $username;
          // $report = Statement::Create($inputs); ->format('d/m/Y') 
          $report->save();  
          
          $product_id = $request->product_id;
          $client_id = $request->client_id;
          if($client_id > 0){
            $client_id = $client_id;
            $client_op = "=";
          }else{
            $client_id = null;
            $client_op = "!=";
          } 

          $productsItem = PurchaseItem::where('client_id',$client_op,$client_id)
                  ->join('credit_notes', 'credit_notes.id', '=', 'credit_notes_items.credit_notes_id')
                  ->where('credit_notes.payment_date', '>=',$from_date)
                  ->where('credit_notes.payment_date', '<=',$to_date)
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> credit_note_id = $reportX->credit_notes_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->credit_note_date = $reportX ->created_at;
                        $reportItem ->status_id = CreditNote::where('id','=',$reportX->credit_notes_id)->value('status_id');
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
        if ($user->is_creditnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $ReportId1 = CreditNoteReport::latest()->take(1)->get();
        foreach ($ReportId1 as $RequestID){
            $reports = ReportItem::with(['credit_notes','uom','product','products','clients','invoices'])
            ->where('report_id','=',$RequestID->id)
            ->where('status_id','>=',1)
            ->where('credit_note_date','>=',$RequestID->from_date)
            ->where('credit_note_date','<=',$RequestID->to_date)
            ->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'getIsHtml5ParserEnabled' => true])->setPaper('a4', 'portrait')->setWarnings(false)->loadView('docs.credit_notes_report',compact('reports'));
           return $pdf->download(now().'credit_notes.pdf');

          
             // return view('docs.purchaser',compact('reports'));
        }
      }
    }


    
    public function excel()
    { 
        $id = CreditNoteReport::latest()->take(1)->value('id');
        return Excel::download(new CreditNoteReportExcel($id), now().'credit_notes.xlsx');
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
      $user = auth()->user();
        if ($user->is_creditnotes_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'user_id' => 'nullable',
            'product_id' => 'nullable',
            'client_id' => 'nullable',
            'created_by' => 'nullable',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
          $username = Auth::user()->id;
          // $inputs = $request->all();
          $report = new CreditNoteReport;
          $report ->user_id = $username;
          $report ->product_id = $request->input('product_id');
          $report ->client_id = $request->input('client_id');
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
          
         

        if ($request->client_id > 0){
                $productsItem = PurchaseItem::where('client_id','=',$request->client_id)
                  ->join('credit_notes', 'credit_notes.id', '=', 'credit_notes_items.credit_notes_id')
                  ->where('credit_notes.payment_date', '>=',$request->input('from_date').' 01:00:00')
                  ->where('credit_notes.payment_date', '<=',$request->input('to_date').' 00:00:00')
                  ->get();
                    foreach ($productsItem as $reportX)
                    {
                        $reportItem = new ReportItem;
                        $reportItem -> report_id = $report->id;
                        $reportItem -> credit_note_id = $reportX->credit_notes_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->credit_note_date = $reportX ->date;
                        $PR = CreditNote::where('id','=',$reportX->credit_notes_id)->get();
                        foreach ($PR as $PRStatus){
                            $reportItem ->status_id = $PRStatus ->status_id;
                        }

                        $reportItem->save();   
                    } 
            }
            else{
                $productsItem = PurchaseItem::join('credit_notes', 'credit_notes.id', '=', 'credit_notes_items.credit_notes_id')
                ->where('credit_notes.payment_date', '>=',$request->input('from_date').' 01:00:00')
                ->where('credit_notes.payment_date', '<=',$request->input('to_date').' 00:00:00')
                ->get();
                  foreach ($productsItem as $reportX)
                  {
                      $reportItem = new ReportItem;
                      $reportItem -> report_id = $report->id;
                        $reportItem -> credit_note_id = $reportX->credit_notes_id;
                        $reportItem ->invoice_id = $reportX->invoice_id;
                        $reportItem ->client_id = $reportX->client_id;
                        $reportItem ->amount_applied = $reportX->amount_applied;
                        $reportItem ->amount_received = $reportX->amount_received;
                        $reportItem ->from_date = $report ->from_date;
                        $reportItem ->to_date = $report ->to_date;
                        $reportItem ->credit_note_date = $reportX ->date;
                      $PR = CreditNote::where('id','=',$reportX->credit_notes_id)->get();
                      foreach ($PR as $PRStatus){
                          $reportItem ->status_id = $PRStatus ->status_id;
                      }

                      $reportItem->save();   
                  } 
            }



          return api([
            'saved' => true,
            'id' => $report->id
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
