<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ClassesCode;
use App\ChartOfAccount;
use App\JournalVoucher\Item as JournalVoucherItem;
   use App\Exports\BalanceSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 

public function index(Request $request)
{
    $search = $request->search;
    $from = $request->from;
    $to = $request->to;

    $classes = ClassesCode::with(['accounts.journalItems' => function($q) {
        $q->whereHas('voucher', function($v) {
            $v->where('status_id', 2);
        });
    }, 'accounts.journalItems.voucher'])
        ->orderBy('code')
        ->get();
    // Export logic
    if($request->has('export')) {
        return Excel::download(
            new BalanceSheetExport($classes, $from, $to, $search),
            'Balance_Sheet_'.now()->format('Ymd_His').'.xlsx'
        );
    }

    return view('balance_sheet.index', compact('classes','search'));
}


public function details($id)
{
    $journal = JournalVoucher::with('items.account')->findOrFail($id);

    return response()->json([
        'id' => $journal->id,
        'number' => $journal->number,
        'date' => $journal->date,
        'description' => $journal->description,
        'document' => $journal->document ? url('/uploads/' . $journal->document) : null,
        'items' => $journal->items->map(fn($itm) => [
            'account_code' => $itm->account->code ?? '',
            'account_name' => $itm->account->name_en ?? '',
            'debit' => $itm->debit,
            'credit' => $itm->credit,
        ]),
    ]);
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
