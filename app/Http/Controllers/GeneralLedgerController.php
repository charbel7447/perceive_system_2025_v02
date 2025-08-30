<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClassesCode;
use App\ChartOfAccount;
use App\JournalVoucher\JournalVoucher;
use App\JournalVoucher\Item;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GeneralLedgerExport;
use PDF;


class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $date_from = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
        $date_to   = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());
        $account_id = $request->input('account_id', null);

        // Load Chart of Accounts grouped by Class
        $classes = ClassesCode::with(['accounts' => function($q) use ($date_from, $date_to, $account_id) {
            if ($account_id) {
                $q->where('id', $account_id);
            }
        }])->get();

        // Collect ledger entries for each account
        $ledgerData = [];
        foreach ($classes as $class) {
            foreach ($class->accounts as $account) {
                $entries = Item::query()
                    ->with('journalVoucher')
                    ->where('account_id', $account->id)
                    ->whereHas('journalVoucher', function($q) use ($date_from, $date_to) {
                        $q->whereBetween('date', [$date_from, $date_to])
                          ->where('status_id', 2); // posted only
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

                $openingBalance = Item::query()
                    ->where('account_id', $account->id)
                    ->whereHas('journalVoucher', function($q) use ($date_from) {
                        $q->where('date', '<', $date_from)
                          ->where('status_id', 2);
                    })
                    ->selectRaw('SUM(debit - credit) as balance')
                    ->value('balance') ?? 0;

                $runningBalance = $openingBalance;

                $rows = [];
                foreach ($entries as $entry) {
                    $runningBalance += ($entry->debit - $entry->credit);
                    $rows[] = [
                        'date'          => $entry->journalVoucher->date,
                        'voucher_no'    => $entry->journalVoucher->number,
                        'voucher_id'    => $entry->journalVoucher->id,
                        'description'   => $entry->description,
                        'debit'         => $entry->debit,
                        'credit'        => $entry->credit,
                        'balance'       => $runningBalance,
                    ];
                }

                $ledgerData[$class->code][] = [
                    'account' => $account,
                    'opening' => $openingBalance,
                    'rows'    => $rows,
                    'closing' => $runningBalance,
                ];
            }
        }

        return view('general_ledger.index', compact(
            'classes', 'ledgerData', 'date_from', 'date_to', 'account_id'
        ));
    }

        public function exportExcel(Request $request)
    {
        return Excel::download(new GeneralLedgerExport($request), 'general_ledger.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        $query = Item::with(['journalVoucher', 'account']);

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('date_from')) {
            $query->whereHas('journalVoucher', function ($q) use ($request) {
                $q->whereDate('date', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('journalVoucher', function ($q) use ($request) {
                $q->whereDate('date', '<=', $request->date_to);
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        $pdf = PDF::loadView('general_ledger.general_ledger_pdf', compact('accounts', 'items', 'request'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('general_ledger.pdf');
    }
}
