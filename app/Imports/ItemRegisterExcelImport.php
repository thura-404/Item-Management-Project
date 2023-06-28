<?php

namespace App\Imports;

use App\Interfaces\ItemInterface;
use App\Imports\getFirstItemSheetImport;
use App\Interfaces\CategoryInterface;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;



/**
 * Get first item sheet import
 * @author Thura Win
 * @create 23/06/2023
 */
class ItemRegisterExcelImport implements WithMultipleSheets
{
    protected $itemInterface, $categoryInterface;

    public function __construct(ItemInterface $itemInterface, CategoryInterface $categoryInterface)
    {
        $this->itemInterface = $itemInterface;
        $this->categoryInterface = $categoryInterface;
    }

     /**
     * Define sheets to import.
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => new getFirstItemSheetImport($this->itemInterface, $this->categoryInterface), // First sheet import
        ];
    }
}
