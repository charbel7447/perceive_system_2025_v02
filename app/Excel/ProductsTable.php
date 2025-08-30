<?php

namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsTable implements FromView, WithStyles, WithTitle, WithEvents, WithColumnFormatting
{
    use Exportable;

    /**
     * Apply styles to the worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Apply bold font to headers
        $sheet->getStyle('A1:DM1')->getFont()->setBold(true)->setSize(12);

        // Apply center alignment to headers
        $sheet->getStyle('A1:DM1')->getAlignment()->setHorizontal('center');

        // Apply gradient fill to headers
        $sheet->getStyle('A1:DM1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'startColor' => ['argb' => '455BBDDD'],
                'endColor' => ['argb' => '455BBDDD'],
            ],
        ]);

        // Apply thin borders to the table
        $sheet->getStyle('A1:DM1')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
    }

    /**
     * Define column formats.
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'E' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    /**
     * Define the title of the worksheet.
     */
    public function title(): string
    {
        return 'Products Table';
    }

    /**
     * Register events for the worksheet.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto-width for all filled columns
                foreach (range('A', 'Z') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                foreach (range('AA', 'DM') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Add auto filter to the header row
                $sheet->setAutoFilter('A1:DM1');
            },
        ];
    }

    /**
     * Provide the view for the export.
     */
    public function view(): View
    {
        return view('home.products_table');
    }
}
