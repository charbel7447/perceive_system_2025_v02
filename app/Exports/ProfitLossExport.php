<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitLossExport implements FromView, WithStyles
{
    protected $report;
    protected $date_from;
    protected $date_to;

    public function __construct(array $report, string $date_from, string $date_to)
    {
        $this->report    = $report;
        $this->date_from = $date_from;
        $this->date_to   = $date_to;
    }

    public function view(): View
    {
        return view('profit_loss.profit_loss_excel', [
            'report'    => $this->report,
            'date_from' => $this->date_from,
            'date_to'   => $this->date_to,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
