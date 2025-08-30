<?php 
namespace App\Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StockMovementBladeExport implements FromView, WithStyles, WithColumnWidths, WithEvents, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.stock_movement_excel', [
            'data' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row A1:J1
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007BFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Style all rows with wrapText and thin borders
        $highestRow = $sheet->getHighestDataRow();
        $sheet->getStyle("A2:J{$highestRow}")->applyFromArray([
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_TOP,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Icon
            'B' => 15,  // Item Code
            'C' => 40,  // Product Name
            'D' => 15,  // Quantity
            'E' => 20,  // Price
            'F' => 20,  // Warehouse
            'G' => 20,  // Category
            'H' => 25,  // Type
            'I' => 20,  // Document
            'J' => 20,  // Date
        ];
    }

    public function title(): string
    {
        return 'Stock Movement';
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ AutoFilter for the header row
                $sheet->setAutoFilter('A1:J1');

                // ✅ Freeze the header row
                $sheet->freezePane('A2');

                // ✅ AutoSize all columns (can override columnWidths if needed)
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
