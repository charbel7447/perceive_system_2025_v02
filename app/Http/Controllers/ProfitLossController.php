<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\ClassesCode;                 // chart_classes model
use App\ChartOfAccount;             // chart_accounts model
use App\JournalVoucher\Item;        // journal_voucher_items model
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ProfitLossController extends Controller
{
    /**
     * Show Profit & Loss report 
     */
    public function index(Request $request)
    {
        // Filters
        $date_from  = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
        $date_to    = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());
        $account_id = $request->input('account_id');

        // Relevant classes for P&L: Revenue(4), Expenses(5), Other Income(6), Other Expenses(7)
        $classCodes = ['4','5','6','7'];

        // Load classes with accounts
        $classes = ClassesCode::whereIn('code', $classCodes)
            ->with(['accounts' => function($q) use ($account_id) {
                if ($account_id) {
                    $q->where('id', $account_id);
                }
            }])->orderBy('code')->get();

        // Compute P&L
        $report = $this->buildProfitLoss($classes, $date_from, $date_to);

        return view('profit_loss.index', [
            'classes'    => $classes,
            'report'     => $report,
            'date_from'  => $date_from,
            'date_to'    => $date_to,
            'account_id' => $account_id,
        ]);
    }

    /**
     * Excel export
     */
    public function exportExcel(Request $request)
    {
        $date_from  = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
        $date_to    = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());
        $account_id = $request->input('account_id');

        $classCodes = ['4','5','6','7'];
        $classes = ClassesCode::whereIn('code', $classCodes)
            ->with(['accounts' => function($q) use ($account_id) {
                if ($account_id) {
                    $q->where('id', $account_id);
                }
            }])->orderBy('code')->get();

        $report = $this->buildProfitLoss($classes, $date_from, $date_to);

        return Excel::download(new \App\Exports\ProfitLossExport($report, $date_from, $date_to), 'profit_loss.xlsx');
    }

    /**
     * PDF export
     */
    public function exportPdf(Request $request)
    {
        $date_from  = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
        $date_to    = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());
        $account_id = $request->input('account_id');

        $classCodes = ['4','5','6','7'];
        $classes = ClassesCode::whereIn('code', $classCodes)
            ->with(['accounts' => function($q) use ($account_id) {
                if ($account_id) {
                    $q->where('id', $account_id);
                }
            }])->orderBy('code')->get();

        $report = $this->buildProfitLoss($classes, $date_from, $date_to);

        $pdf = PDF::loadView('profit_loss.profit_loss_pdf', [
            'report'    => $report,
            'date_from' => $date_from,
            'date_to'   => $date_to,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('profit_loss.pdf');
    }

        public function accountDocs($accountId)
    {
        $docs = Item::where('account_id', $accountId)
                    ->with('journalVoucher')
                    ->get()
                    ->map(fn($i) => [
                        'id' => $i->journalVoucher->id,
                        'date' =>  $i->journalVoucher->date,
                        'name' => $i->journalVoucher->number ?? 'Doc #'.$i->journalVoucher->number,
                    ]);

        return response()->json($docs);
    }

    /**
     * Build Profit & Loss data structure
     *
     * For Income (4,6): amount = credit - debit
     * For Expenses (5,7): amount = debit - credit
     */
    protected function buildProfitLoss($classes, $date_from, $date_to)
    {
        $data = [];
        $totalIncome = 0.0;
        $totalExpense = 0.0;

        foreach ($classes as $class) {
            $classCode = $class->code;
            $className = $class->name_en;

            $accountsArr = [];

            foreach ($class->accounts as $account) {
                // Sum debit/credit in date range for this account (posted only)
                $sum = Item::query()
                    ->where('account_id', $account->id)
                    ->whereHas('journalVoucher', function($q) use ($date_from, $date_to) {
                        $q->whereBetween('date', [$date_from, $date_to])
                          ->where('status_id', 2);   // ✅ Only posted vouchers
                    })
                    ->selectRaw('COALESCE(SUM(debit),0) as t_debit, COALESCE(SUM(credit),0) as t_credit')
                    ->first();

                $debit  = (float) ($sum->t_debit ?? 0);
                $credit = (float) ($sum->t_credit ?? 0);

                // Income classes: positive when credit > debit
                if (in_array($classCode, ['4','6'])) {
                    $amount = $credit - $debit;
                } else { // Expense classes: positive when debit > credit
                    $amount = $debit - $credit;
                }

                if (abs($amount) > 0.0001) {
                    $accountsArr[] = [
                        'id'    => $account->id,
                        'code'  => $account->code,
                        'name'  => $account->name_en,
                        'amount'=> $amount,
                    ];
                }
            }

            // Subtotal per class
            $subtotal = array_reduce($accountsArr, fn($c, $x) => $c + $x['amount'], 0.0);

            if (in_array($classCode, ['4','6'])) {
                $totalIncome += $subtotal;
            } else {
                $totalExpense += $subtotal;
            }

            $data[$classCode] = [
                'class_name' => $className,
                'accounts'   => $accountsArr,
                'subtotal'   => $subtotal,
            ];
        }

        $netProfit = $totalIncome - $totalExpense;

        return [
            'classes'      => $data,
            'total_income' => $totalIncome,
            'total_expense'=> $totalExpense,
            'net_profit'   => $netProfit,
        ];
    }
}
