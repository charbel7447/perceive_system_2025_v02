<?php

namespace App\Exports;

use App\JournalVoucher\Item;
use App\ChartOfAccount;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class GeneralLedgerExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Item::with('journalVoucher')->orderBy('created_at', 'asc');

        if ($this->request->filled('account_id')) {
            $query->where('account_id', $this->request->account_id);
        }

        if ($this->request->filled('date_from')) {
            $query->whereHas('journalVoucher', function ($q) {
                $q->whereDate('date', '>=', $this->request->date_from);
            });
        }

        if ($this->request->filled('date_to')) {
            $query->whereHas('journalVoucher', function ($q) {
                $q->whereDate('date', '<=', $this->request->date_to);
            });
        }

        $items = $query->get();

        return view('general_ledger.general_ledger_excel', [
            'items' => $items
        ]);
    }
}
