<?php

namespace App\JournalVoucher;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromView, WithStyles, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $accountId;

    public function __construct($fromDate, $toDate, $accountId = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->accountId = $accountId;
    }

    public function view(): View
    {
        $query = DB::table('journal_voucher_items as jvi')
            ->join('journal_vouchers as jv', 'jvi.journal_voucher_id', '=', 'jv.id')
            ->join('chart_accounts as ca', 'jvi.account_id', '=', 'ca.id')
            ->join('chart_classes as cc', 'ca.class_code', '=', 'cc.code')
            ->select(
                'jvi.account_id as account_id',
                'ca.code as account_code',
                'ca.name_en as account_name_en',
                'ca.name_ar as account_name_ar',
                'cc.name_en as class_name_en',
                'cc.name_ar as class_name_ar',
                DB::raw('SUM(jvi.debit) as total_debit'),
                DB::raw('SUM(jvi.credit) as total_credit'),
                DB::raw('SUM(jvi.debit - jvi.credit) as balance')
            )
             ->where('jv.status_id','=',2)
            ->groupBy('jvi.account_id', 'ca.code', 'ca.name_en', 'ca.name_ar', 'cc.name_en', 'cc.name_ar')
            ->orderBy('cc.code')
            ->orderBy('ca.code');

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('jv.date', [$this->fromDate, $this->toDate]);
        }

        if ($this->accountId) {
            $query->where('jvi.account_id', $this->accountId);
        }

        $trialBalances = $query->get();

        return view('reports.trial_balance_excel', compact('trialBalances'));
    }

    // Apply base styles (text align right for numeric columns)
    public function styles(Worksheet $sheet)
    {
        // Right align all numeric columns: Debit, Credit, Balance (columns D, E, F)
        return [
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
            'E' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
            'F' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],

            // Header row style: row 1, blue background, white bold text, wrap text
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' =>  ['rgb' => '0070C0'], // Bootstrap primary blue
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    // Use events to adjust column width automatically after sheet creation
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto size all columns A to F
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Enable autofilter on header row (A1:F1)
                $sheet->setAutoFilter('A1:H1');
            },
        ];
    }
}
