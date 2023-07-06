<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * handle item register format download
 * @author Thura Win
 * @create 23/06/2023
 */
class ItemRegiserFormatExport implements WithMultipleSheets
{
    /**
     * @author Thura Win
     * @create 23/06/2023
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new class implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithEvents
        {
            /**
             * @author Thura Win
             * @create 23/06/2023
             */
            public function collection()
            {
                return collect([]);
            }

            /**
             * @author Thura Win
             * @create 23/06/2023
             */
            public function title(): string
            {
                return 'Items Data';
            }

            /**
             * @author Thura Win
             * @create 23/06/2023
             */
            public function headings(): array
            {
                return [
                    ['The Red Fields Can\'t be empty. ' . chr(10) . 'There should be only 100 records.' . chr(10) . 'Fill the Category Name from the Category Sheet'],
                    ['Item Code', 'Item Name', 'Category Name', 'Safety Stock', 'Receieved Date', 'Description']
                ];
            }

            /**
             * @author Thura Win
             * @create 23/06/2023
             */
            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $cellRangeHeader = 'A1:F2'; // Cell range for headers
                        $cellRangeData = 'A2:F2'; // Cell range for data (replace 999 with the last row number)
                        $redHeaders = "A2:E2"; // Cell range for headers"

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


                        $event->sheet->getDelegate()->getStyle('A2:F2')->applyFromArray([
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

                        $event->sheet->getDelegate()->getStyle($redHeaders)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000'], // Red font color
                            ],
                        ]);

                        $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000'], // Red font color
                            ],
                            'row' => [
                                'height' => 50, // Set the desired row height
                            ],
                        ]);

                        $event->sheet->getStyle('A1')->getAlignment()->setWrapText(true);

                        $event->sheet->getDelegate()->getStyle($cellRangeHeader)->getFont()->setSize(14);
                        $event->sheet->getDelegate()->mergeCells('A1:F1');
                        // Adjust row height to accommodate the border
                        $worksheet = $event->sheet->getDelegate();
                        $rowHeight = 25; // Adjust this value as needed
                        $worksheet->getDefaultRowDimension()->setRowHeight($rowHeight);
                        $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(60);
                        // $worksheet->getRowDimension(1)->setRowHeight($rowHeight);
                        // $worksheet->getRowDimension(2)->setRowHeight($rowHeight);

                    },
                ];
            }
        };

        $sheets[] = new CategoriesExport();

        return $sheets;
    }
}
