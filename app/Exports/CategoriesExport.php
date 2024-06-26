<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * handle the export of categories in excel file
 * @author Thura Win
 * @create 23/06/2023
 */
class CategoriesExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @author Thura Win
     * @create 23/06/2023
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::select('id', 'name')->get();
    }

    /**
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function title(): string
    {
        return 'Categories';
    }

    /**
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function headings(): array
    {
        return ['ID', 'Name'];
    }

    /**
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRangeHeader = 'A1:B1'; // Cell range for headers
                $cellRangeData = 'A2:B999'; // Cell range for data (replace 999 with the last row number)

                // Set dark blue background and white font color for A1 and B1
                $darkBlueColor = new Color();
                $darkBlueColor->setRGB('9DB2BF');
                $borderColor = new Color();
                $borderColor->setRGB('DDE6ED');
                $fontColor = new Color();
                $fontColor->setRGB('27374D');
                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // White font color
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $darkBlueColor->getRGB()], // Dark blue background
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center align text horizontally
                        'vertical' => Alignment::VERTICAL_CENTER, // Center align text vertically
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A2:B2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $borderColor->getRGB()], // Dark blue border color
                        ],
                    ]
                ]);

                // Set dark blue font color and white background for A2 and B2 onwards
                $event->sheet->getDelegate()->getStyle($cellRangeData)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => $fontColor->getRGB()], // Dark blue font color
                    ],
                    // 'fill' => [
                    //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    //     'startColor' => ['rgb' => 'FFFFFF'], // White background
                    // ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $borderColor->getRGB()], // Dark blue border color
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center align text horizontally
                        'vertical' => Alignment::VERTICAL_CENTER, // Center align text vertically
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->getFont()->setSize(14);

                // Adjust row height to accommodate the border
                $worksheet = $event->sheet->getDelegate();
                $rowHeight = 25; // Adjust this value as needed
                $worksheet->getDefaultRowDimension()->setRowHeight($rowHeight);
                // $worksheet->getRowDimension(1)->setRowHeight($rowHeight);
                // $worksheet->getRowDimension(2)->setRowHeight($rowHeight);

                // Enable text wrapping
            },
        ];
    }
}
