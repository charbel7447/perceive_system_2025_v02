<?php

namespace App\Excel;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithHeadings	;
use Maatwebsite\Excel\Events\BeforeSheet;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Concerns\FromCollection;




class PriceChange implements FromView,WithStyles,WithTitle,WithEvents,WithColumnFormatting
{
    use Exportable;
    public $id;
    
    public function __construct(int $id){
        $this->id = $id;
        return $this;
    }

    public function styles(Worksheet $sheet)
    {
        $cellRange = 'A1:L1'; // All headers
        $styleArray = [
            'borders' => [
                'allborders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'type'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            ],
        ];

        $styleArray1 = [
            'font' => [
                'bold' => true,
                ]
            ];

        $styleArray2 = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				 )
		);	

        $styleArray4 = array(
						'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        ]
					);
		
		$styleArray5 = array(
						'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        
        ]);
        
    }

     public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'E' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function title(): string
    {
        return 'PriceChanges';
    }


    public function registerEvents(): array
    {
       
   
        $styleArray1 = [
            'font' => [
                'bold' => true,
                ]
            ];

        $styleArray2 = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				 )
		);	

		
		$styleArray5 = array(
						'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        
      ]);

    

        $styleArray8 = array(
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  12,
                'bold'      =>  true,
            
            ],
        );
       

        $styleArray4 = array(
            'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'startColor' => [
            'argb' => '455bbddd', //1a7cc3e8
            ],
            'endColor' => [
            'argb' => '455bbddd',
            ]]
        );

        return [
            AfterSheet::class => function(AfterSheet $event) use ($styleArray1, $styleArray4, $styleArray2, 
            $styleArray5, $styleArray8)
			{
                		$cellRange = 'A1:L1'; // All headers
                		$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);

                $event->sheet->setAutoFilter('A1:L1');        
				
							
//Heading formatting...
				$event->getSheet()->getDelegate()->getStyle('A2:L2')->applyFromArray($styleArray1);

                $event->getSheet()->getDelegate()->getStyle('A1:L1')->applyFromArray($styleArray8);

                $event->getSheet()->getDelegate()->getStyle('A1:L1')->applyFromArray($styleArray4);
							
			
				//column width set							
				$event ->sheet-> getDelegate()->getColumnDimension('A')->setWidth(115);
				$event ->sheet-> getDelegate()->getColumnDimension('B')->setWidth(15);
				$event ->sheet-> getDelegate()->getColumnDimension('C')->setWidth(15);
				$event ->sheet-> getDelegate()->getColumnDimension('D')->setWidth(15);
				$event ->sheet-> getDelegate()->getColumnDimension('E')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('F')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('G')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('H')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('I')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('J')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('K')->setWidth(15);
                $event ->sheet-> getDelegate()->getColumnDimension('L')->setWidth(15);
               
		    },
        ];

    }
   
            
    public function view(): View
    {
    //    dd($this->id);
        return view('home.price_change_report', [
            'id' => $this->id
        ]);
    }
}