<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JournalVoucher\JournalVoucher;
use App\JournalVoucher\Item;
use App\JournalVoucher\JournalVoucherMovement;
use App\JournalVoucher\JournalFlowMapping;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\VatRate\VatRate;
use Carbon\Carbon;

use App\Settings;
use App\User;

use App\JournalVoucher\MovementExcel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesOrderController;

class JournalVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => JournalVoucher::with('currency')->search()
            ]);
        }
    }

    
    public function movement()
    {
        $movements = JournalVoucherMovement::orderByDesc('movement_date')->get();
        return view('journal_vouchers_movement', compact('movements'));
    }


     public function flow()
    {
        $accounts = \App\ChartOfAccount::all();
        $sections = [
            'invoice',
            'vendor_payment',
            'client_payment',
            'advance_payment',
            'bill',
            'credit_note',
            'debit_note',
            'expenses',
            'purchase_order',
            'sales_order',
        ];

        $fields = [
            'Total Amount',
            'Sub Total',
            'VAT',
            'Charges',
            'Discount',
        ];

        $savedMappings = JournalFlowMapping::all()->keyBy('process');
        return view('journal_vouchers_flow', compact('accounts', 'sections', 'fields', 'savedMappings'));
    }

    public function storeFlow(Request $request)
    {
        $flows = $request->input('flows', []);

        foreach ($flows as $process => $fields) {
            $mappedFields = [];

            foreach ($fields as $index => $fieldData) {
                $mappedFields[] = [
                    'field' => $this->mapFieldLabel($index),
                    'account_id' => $fieldData['account_id'] ?? null,
                    'type' => $fieldData['type'] ?? null,
                ];
            }

            JournalFlowMapping::updateOrCreate(
                ['process' => $process],
                ['mappings' => $mappedFields]
            );
        }

        return back()->with('success', 'Journal flow mappings saved successfully.');
    }

    // Optional helper to convert index to readable label (if needed)
    protected function mapFieldLabel($index)
    {
        $map = [
            0 => 'Total Amount',
            1 => 'Sub Total',
            2 => 'VAT',
            3 => 'Charges',
            4 => 'Discount',
        ];
        return $map[$index] ?? 'Unknown';
    }

     public function export_excel()
    { 
        return Excel::download(new MovementExcel, now().'journals.xlsx');
    }
    

    public function search()
    {
        $results = JournalVoucher::with('client','currency')
            ->orderBy('id','desc')
            ->when(request('q'), function($query) {
                $query->where('number', 'like', '%'.request('q').'%');
                //->orWhere('description', 'like', '%'.request('q').'%');
            })
            ->limit(15)
            ->get();
            return api([
                'results' => $results
            ]);
    }

    
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'user_id' => 'sometimes|required|integer|exists:clients,id'
            ]);
            $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            $vatrate = VatRate::where('currency2','=',2)->take(1)->latest()->value('value2');
            $form = [
            
                'number' => counter()->next('journal_vouchers'),
                'date' => date('Y-m-d'),
                'exchange_rate' => $exchange,
                'vat_rate' => $vatrate,
                'items' => [
                    [
                        'account_id' => null,
                        'debit' => 0,
                        'credit' => 0
                    ]
                ]
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
        if ($user->is_journalvoucher_create == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'currency_id' => 'required|integer|exists:currencies,id',
                'reference' => 'nullable|max:255',
                'date' => 'required|date_format:Y-m-d',
                'document_type' => 'required',
                'items' => 'required|array|min:1',
                'items.*.account_id' => 'required|integer'
            ]);

            $items = collect($request->items);
            $total_credit = $items->sum(function($item) {
                return $item['credit'];
            });
            $total_debit = $items->sum(function($item) {
                return $item['debit'];
            });

            if($total_credit != $total_debit){
                throw new \Exception('Total Debit/Credit Mismatch '.$total_credit.' != '.$total_debit);
            }
            $model = new JournalVoucher();
            $model->fill($request->except('items'));
            $model->user_id = auth()->id();
            $model->status_id = JournalVoucher::DRAFT;
            $model->saved_at = now();
            $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
           
            // upload document if exists
            if($request->hasFile('document') && $request->file('document')->isValid()) {
                // store in public uploads folder by default
                if($fileName = uploadFile($request->document)) {
                        $model->document = $fileName;
                }
            }

            $model->total_credit = $total_credit;
            $model->total_debit = $total_debit;
            $username = Auth::user()->name;
            $model ->created_by = $username;
            $model = DB::transaction(function() use ($model, $request) {
                $model->number = counter()->next('journal_vouchers');
                $model->storeHasMany([
                    'items' => $request->items
                ]);
                counter()->increment('journal_vouchers');
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
        if ($user->is_journalvoucher_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data =  JournalVoucher::with(['items','invoices','bills','purchase_orders','sales_orders','currency','items.account'])
            ->findOrFail($id);
        return api([
            'data' => $data
        ]);
            }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = JournalVoucher::with(['items','invoices','bills','purchase_orders','sales_orders','currency','items.account'])->findOrFail($id);


        return api([
            'form' => $form
        ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_edit == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = JournalVoucher::findOrFail($id);

            $request->validate([
                'currency_id' => 'required|integer|exists:currencies,id',
                'reference' => 'nullable|max:255',
                'date' => 'required|date_format:Y-m-d',
                'document_type' => 'required',
                'items' => 'required|array|min:1',
                'items.*.account_id' => 'required|integer'
            ]);

            $items = collect($request->items);
            $total_credit = $items->sum(function($item) {
                return $item['credit'];
            });
            $total_debit = $items->sum(function($item) {
                return $item['debit'];
            });

            if($total_credit != $total_debit){
                throw new \Exception('Total Debit/Credit Mismatch '.$total_credit.' != '.$total_debit);
            }
            $model->fill($request->except('items'));
            $model->user_id = auth()->id();
            $model->status_id = JournalVoucher::DRAFT;
            $model->saved_at = now();
            $model->year_date = Carbon::createFromFormat('Y-m-d', $request->date)->year;
           
            // upload document if exists
            if($request->hasFile('document') && $request->file('document')->isValid()) {
                // store in public uploads folder by default
            if($fileName = uploadFile($request->document)) {
                    // overwrite previous uploaded file
                    deleteFile($model->document);
                    $model->document = $fileName;
            }
            }

            $model->total_credit = $total_credit;
            $model->total_debit = $total_debit;
       
            $username = Auth::user()->name;
            $model ->created_by = $username;
        
            $model = DB::transaction(function() use ($model, $request) {
                $model->updateHasMany([
                    'items.taxes' => $request->items
                ]);
                return $model;
            });

            return api([
                'saved' => true,
                'id' => $model->id
            ]);
        }
    }


    public function markAs($id, Request $request)
    {
        // throw new \Exception ("if when convert other function run, should handle this maybe by acting like you confirm the document");
        $model = JournalVoucher::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|in:1,2,3,6,7,9'
        ]);

        switch ($request->status) {
            case (JournalVoucher::POSTED) :
                // must be draft
                abort_if(!in_array($model->status_id, [
                    JournalVoucher::DRAFT
                ]), 404);
               
                $document_type = $model->document_type;
                $document_id = $model->document_id;
                if($document_type == 1){
                    $document = \App\Invoice\Invoice::findorfail($document_id);
                }
                if($document_type == 2){
                    $document = \App\Bill\Bill::findorfail($document_id);
                }
                if($document_type == 3){
                    $document = \App\PurchaseOrder\PurchaseOrder::findorfail($document_id);
                }
                if($document_type == 4){
                    $document = \App\SalesOrder\SalesOrder::findorfail($document_id);
                }
                if($document_type == 5){
                    $document = null;
                }

                $manual_type  = $model->manual_type;
                // throw new \Exception ($manual_type);
                if($manual_type == 'credit_notes'){
                    $document = \App\CreditNote\CreditNote::findorfail($document_id);
                }
                if($manual_type == 'debit_notes'){
                    $document = \App\CreditNote\CreditNote::findorfail($document_id);
                }
                if($manual_type == 'advance_payments'){
                    $document = \App\AdvancePayment\AdvancePayment::findorfail($document_id);
                }
                if($manual_type == 'expenses'){
                    $document = \App\Expense\Expense::findorfail($document_id);
                }
                if($manual_type == 'client_payments'){
                    $document = \App\ClientPayment\ClientPayment::findorfail($document_id);
                }
                if($manual_type == 'vendor_payments'){
                    $document =    \App\VendorPayment\VendorPayment::findorfail($document_id);
                }
                if($manual_type == 'receipt_vouchers'){
                    $document = \App\ReceiptVoucher\ReceiptVoucher::findorfail($document_id);
                }

                // throw new \Exception ($document_type.''.$document->number);
                if(!empty($document)){
                    if($document->posted == 1){
                        $journal = $document->journal_id;
                        $journal = JournalVoucher::findorfail($journal);
                        
                        throw new \Exception ('Document Already Posted, check journal: '.$journal->number);
                    }else{
                        $model->status_id = JournalVoucher::POSTED;

                        $document_id = $model->document_id;

                        $username = Auth::user()->name;
                        $model->status_id = JournalVoucher::POSTED;
                        $model->posted_at = now();
                        $model->posted_by = $username;
                    
                        $document_status_id = $document->status_id;


                    

                        if($document_type == 1){
                            \App\Invoice\Invoice::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($document_type == 2){
                            \App\Bill\Bill::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($document_type == 3){
                            \App\PurchaseOrder\PurchaseOrder::where('id','=',$document_id)
                                ->update(['posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($document_type == 4){
                            \App\SalesOrder\SalesOrder::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }

                        if($manual_type == 'credit_notes'){
                            \App\CreditNote\CreditNote::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($manual_type == 'debit_notes'){
                            \App\CreditNote\CreditNote::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($manual_type == 'advance_payments'){
                            \App\AdvancePayment\AdvancePayment::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($manual_type == 'expenses'){
                            \App\Expense\Expense::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($manual_type == 'client_payments'){
                        \App\ClientPayment\ClientPayment::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        if($manual_type == 'vendor_payments'){
                        \App\VendorPayment\VendorPayment::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }

                        if($manual_type == 'receipt_vouchers'){
                        \App\ReceiptVoucher\ReceiptVoucher::where('id','=',$document_id)
                                ->update([ 'posted' => 1 , 'journal_id' => $model->id , 'posted_at' => now()]);
                        }
                        
                        //after updating status proceed with mark as
                            if( $document_type == 1){
                                if( $document_status_id != 2){
                                    $request = new Request(['status' => 2]);
                                    app(InvoiceController::class)->markAs($document_id,$request);
                                    break;
                                }
                            }if( $document_type == 2){
                                // if( $document_status_id != 2){
                                //     $request = new Request(['status' => 3]);
                                //     app(BillController::class)->markAs($document_id,$request);
                                //     break;
                                // }

                            }if( $document_type == 3){
                                if( $document_status_id != 3){
                                    $request = new Request(['status' => 3]);
                                    app(PurchaseOrderController::class)->markAs($document_id,$request);
                                    break;
                                }

                            }if( $document_type == 4){
                                if( $document_status_id != 3){
                                    $request = new Request(['status' => 3]);
                                    app(SalesOrderController::class)->markAs($document_id,$request);
                                    break;
                                }
                            }

                        $movement = new JournalVoucherMovement;
                        $movement->user_id = $model->user_id;
                        $movement->created_by = $model->created_by;
                        $movement->journal_voucher_id = $model->id;
                        $movement->number = $model->number;
                        $movement->currency_id = $model->currency_id;
                        $movement->document_type = $model->document_type;
                        $movement->document_date = $model->document_date;
                        if($model->document_type == 1){
                            $movement->document_name = 'Sales Invoice';
                        }
                        if($model->document_type == 2){
                            $movement->document_name = 'Purchase Invoice (Vendor Bill)';
                        }
                        if($model->document_type == 3){
                            $movement->document_name = 'Purchase Order';
                        }
                        if($model->document_type == 4){
                            $movement->document_name = 'Sales Order';
                        }
                        if($model->document_type == 5){
                            $movement->document_name = 'Manual Journal Entry (No linked doc)';
                        }
                        $movement->type = 'Added';
                        $movement->movement_date = now();
                        $movement->date = $model->date;
                        $movement->exchange_rate = $model->document_type;
                        $movement->vat_rate = $model->vat_rate;
                        $movement->reference = $model->document_type;
                        $movement->year_date = $model->year_date;
                        $movement->total_debit = $model->total_debit;
                        $movement->total_credit = $model->total_credit;
                        $movement->document_id = $model->document_id;
                        $movement->document_number = $model->document_number;
                        $movement->document_total = $model->document_total;
                        $movement->document_currency_id = $model->document_currency_id;
                        $movement->items =\App\JournalVoucher\Item::where('journal_voucher_id','=',$model->id)->get();
                        $movement->save();
                    }
                }else{
                        $model->status_id = JournalVoucher::POSTED;
                        $username = Auth::user()->name;
                        $model->status_id = JournalVoucher::POSTED;
                        $model->posted_at = now();
                        $model->posted_by = $username;

                        $movement = new JournalVoucherMovement;
                        $movement->user_id = $model->user_id;
                        $movement->created_by = $model->created_by;
                        $movement->journal_voucher_id = $model->id;
                        $movement->number = $model->number;
                        $movement->currency_id = $model->currency_id;
                        $movement->document_type = $model->document_type ?? null;
                        $movement->document_date = $model->document_date ?? null;
                        if($model->document_type == 1){
                            $movement->document_name = 'Sales Invoice';
                        }
                        if($model->document_type == 2){
                            $movement->document_name = 'Purchase Invoice (Vendor Bill)';
                        }
                        if($model->document_type == 3){
                            $movement->document_name = 'Purchase Order';
                        }
                        if($model->document_type == 4){
                            $movement->document_name = 'Sales Order';
                        }
                        if($model->document_type == 5){
                            $movement->document_name = 'Manual Journal Entry (No linked doc)';
                        }
                        $movement->type = 'Added';
                        $movement->movement_date = now();
                        $movement->date = $model->date;
                        $movement->exchange_rate = $model->document_type;
                        $movement->vat_rate = $model->vat_rate;
                        $movement->reference = $model->document_type;
                        $movement->year_date = $model->year_date;
                        $movement->total_debit = $model->total_debit;
                        $movement->total_credit = $model->total_credit;
                        $movement->document_id = $model->document_id;
                        $movement->document_number = $model->document_number;
                        $movement->document_total = $model->document_total;
                        $movement->document_currency_id = $model->document_currency_id;
                        $movement->items =\App\JournalVoucher\Item::where('journal_voucher_id','=',$model->id)->get();
                        $movement->save();
                }
                

                $model->status_id = JournalVoucher::POSTED;
                break;


            default:
                abort(404, 'Invalid Operation');
                break;
        }

        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id,
            'status_id' => $model->status_id,
            'is_editable' => $model->is_editable
        ]);
    }

        public function pdf($id, Request $request)
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_view == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $data = JournalVoucher::with(['items','invoices','bills','purchase_orders','sales_orders','currency','items.account'])->findOrFail($id);
        $doc  = 'docs.journal_voucher';

        return pdf($doc, $data);
    }}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_journalvoucher_delete == 0 && $user->is_admin != 1){
            return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = JournalVoucher::findOrFail($id);

                $document_type = $model->document_type;
                $document_id = $model->document_id;
                if($document_type == 1){
                    $document = \App\Invoice\Invoice::where('id','=',$document_id)
                        ->update(['status_id' => 1, 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                }
                if($document_type == 2){
                    $document = \App\Bill\Bill::where('id','=',$document_id)
                        ->update(['status_id' => 1, 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                }
                if($document_type == 3){
                    $document = \App\PurchaseOrder\PurchaseOrder::where('id','=',$document_id)
                        ->update(['status_id' => 2, 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                }
                if($document_type == 4){
                    $document = \App\SalesOrder\SalesOrder::where('id','=',$document_id)
                        ->update(['status_id' => 2, 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                }

                $manual_type  = $model->manual_type;

                 if($manual_type == 'credit_notes'){
                        \App\CreditNote\CreditNote::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => now()]);
                    }
                    if($manual_type == 'debit_notes'){
                        \App\CreditNote\CreditNote::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    if($manual_type == 'advance_payments'){
                        \App\AdvancePayment\AdvancePayment::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    if($manual_type == 'expenses'){
                        \App\Expense\Expense::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    if($manual_type == 'client_payments'){
                       \App\ClientPayment\ClientPayment::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    if($manual_type == 'vendor_payments'){
                       \App\VendorPayment\VendorPayment::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    if($manual_type == 'receipt_vouchers'){
                       \App\ReceiptVoucher\ReceiptVoucher::where('id','=',$document_id)
                            ->update([ 'posted' => null , 'journal_id' => null , 'posted_at' => null]);
                    }
                    

            $movement = new JournalVoucherMovement;
            $movement->user_id = $model->user_id;
            $movement->journal_voucher_id = $model->id;
            $movement->created_by = $model->created_by;
            $movement->number = $model->number;
            $movement->currency_id = $model->currency_id;
            $movement->document_type = $model->document_type;
            $movement->document_date = $model->document_date;
            if($model->document_type == 1){
                $movement->document_name = 'Sales Invoice';
            }
            if($model->document_type == 2){
                $movement->document_name = 'Purchase Invoice (Vendor Bill)';
            }
            if($model->document_type == 3){
                $movement->document_name = 'Purchase Order';
            }
            if($model->document_type == 4){
                $movement->document_name = 'Sales Order';
            }
            if($model->document_type == 5){
                $movement->document_name = 'Manual Journal Entry (No linked doc)';
            }
            $movement->type = 'Deleted';
            $movement->movement_date = now();
            $movement->date = $model->date;
            $movement->exchange_rate = $model->document_type;
            $movement->vat_rate = $model->vat_rate;
            $movement->reference = $model->document_type;
            $movement->year_date = $model->year_date;
            $movement->total_debit = $model->total_debit;
            $movement->total_credit = $model->total_credit;
            $movement->document_id = $model->document_id;
            $movement->document_number = $model->document_number;
            $movement->document_total = $model->document_total;
            $movement->document_currency_id = $model->document_currency_id;
            $movement->items =\App\JournalVoucher\Item::where('journal_voucher_id','=',$model->id)->get();
            $movement->save();

            $model->items()->delete();
            $model->delete();
            return api([
                'deleted' => true
            ]);
        }
    }
}
