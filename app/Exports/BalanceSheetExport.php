<?php

namespace App\Exports;

use App\ClassesCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BalanceSheetExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $classes;
    protected $from;
    protected $to;
    protected $search;

    public function __construct($classes, $from = null, $to = null, $search = null)
    {
        $this->classes = $classes;
        $this->from = $from;
        $this->to = $to;
        $this->search = $search;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->classes as $class) {
            $classSubtotalDebit = 0;
            $classSubtotalCredit = 0;
            $classSubtotalBalance = 0;
            $classHasData = false;

            foreach ($class->accounts as $account) {
                $items = $account->journalItems;

                if($this->from) $items = $items->filter(fn($i) => $i->voucher && $i->voucher->date >= $this->from);
                if($this->to) $items = $items->filter(fn($i) => $i->voucher && $i->voucher->date <= $this->to);
                if($this->search) {
                    $s = strtolower($this->search);
                    if(!(str_contains(strtolower($account->code), $s) ||
                         str_contains(strtolower($account->name_en), $s) ||
                         str_contains(strtolower($account->name_ar), $s))){
                        continue;
                    }
                }

                $debit = $items->sum('debit');
                $credit = $items->sum('credit');
                $balance = $debit - $credit;

                if($items->count() > 0 || $debit != 0 || $credit != 0) {
                    $classHasData = true;

                    $rows->push([
                        'type' => 'account',
                        'class_code' => $class->code,
                        'class_name' => $class->name_en . ' / ' . $class->name_ar,
                        'account_code' => $account->code,
                        'account_name' => $account->name_en . ' / ' . $account->name_ar,
                        'debit' => $debit,
                        'credit' => $credit,
                        'balance' => $balance,
                    ]);

                    $classSubtotalDebit += $debit;
                    $classSubtotalCredit += $credit;
                    $classSubtotalBalance += $balance;
                }
            }

            if($classHasData) {
                // Add class subtotal row
                $rows->push([
                    'type' => 'subtotal',
                    'class_code' => $class->code,
                    'class_name' => $class->name_en . ' / ' . $class->name_ar,
                    'account_code' => '',
                    'account_name' => 'Subtotal',
                    'debit' => $classSubtotalDebit,
                    'credit' => $classSubtotalCredit,
                    'balance' => $classSubtotalBalance,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Class Code',
            'Class Name',
            'Account Code',
            'Account Name',
            'Debit',
            'Credit',
            'Balance',
        ];
    }

    public function map($row): array
    {
        return [
            $row['class_code'],
            $row['class_name'],
            $row['account_code'],
            $row['account_name'],
            $row['debit'],
            $row['credit'],
            $row['balance'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Header style
                $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:G1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');

                // Iterate rows for styling
                for($row = 2; $row <= $highestRow; $row++) {
                    $accountName = $sheet->getCell("D$row")->getValue();

                    // Subtotal rows
                    if(str_contains($accountName, 'Subtotal')) {
                        $sheet->getStyle("A$row:G$row")->getFont()->setBold(true);
                        $sheet->getStyle("A$row:G$row")->getFill()->setFillType(Fill::FILL_SOLID)
                              ->getStartColor()->setRGB('FCE4D6'); // light orange
                    }

                    // Debit green, Credit red, Balance conditional
                    $sheet->getStyle("E$row")->getFont()->getColor()->setRGB('008000');
                    $sheet->getStyle("F$row")->getFont()->getColor()->setRGB('FF0000');

                    $balance = $sheet->getCell("G$row")->getValue();
                    if($balance >= 0){
                        $sheet->getStyle("G$row")->getFont()->getColor()->setRGB('008000'); // green
                    } else {
                        $sheet->getStyle("G$row")->getFont()->getColor()->setRGB('FF0000'); // red
                    }
                }

                // Auto size columns
                foreach(range('A','G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
