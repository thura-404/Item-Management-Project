<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Item;
use App\Interfaces\ItemInterface;
use Illuminate\Support\Collection;
use App\Interfaces\CategoryInterface;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;


/**
 * Import the first item sheet.
 * @author Thura Win
 * @create 23/06/2023
 */
class getFirstItemSheetImport implements ToCollection
{

    protected $itemInterface, $categoryInterface;

    public function __construct(ItemInterface $itemInterface, CategoryInterface $categoryInterface)
    {
        $this->itemInterface = $itemInterface;
        $this->categoryInterface = $categoryInterface;
    }

    /**
     * Import a collection of rows.
     *
     * @author Thura Win
     * @create 26/06/2023
     * @param  \Illuminate\Support\Collection  $rows
     * @return \App\Models\Student|\App\Traits\ResponseAPI
     * @throws \Exception
     */
    public function collection(Collection $rows)
    {
        try {
            // $dataRows = $rows->slice(2); // Exclude the first row (header row)
            $takeHeaders = $rows->slice(1);

            $headerRow = $takeHeaders->first(); // Get the header row from the Excel data

            $dataRows = $rows->slice(2); // Exclude the first row (header row)

            $headerColumns = $headerRow->toArray(); // Convert the header row to an array

            // Create an array to hold attribute names
            $attributeNames = [];

            foreach ($headerColumns as $index => $column) {
                // Set the attribute name as the corresponding header column
                $attributeNames["*.{$index}"] = $column;
            }

            $validator = Validator::make($dataRows->toArray(), [ //validate the data
                '*.0' => 'required',
                '*.1' => 'required',
                '*.2' => 'required|exists:categories,name',
                '*.3' => 'required|numeric',
                '*.4' => 'required',
            ])->setAttributeNames($attributeNames);

            if ($dataRows->isEmpty()) { //if there is no records in Excel file.
                $errors = ['No records found.'];
                throw ValidationException::withMessages(['validation_error' => $errors]);
            }

            if ($dataRows->count() > 100) { //if there are more than 100 records in Excel file.
                $errors = ['The maximum number of records allowed is 100.'];
                throw ValidationException::withMessages(['validation_error' => $errors]);
            }


            if ($validator->fails()) { // If there are any validation errors.
                $errors = [];

                $dataRowsArray = $dataRows->toArray();

                foreach ($validator->errors()->messages() as $field => $fieldErrors) {
                    $index = explode('.', $field)[0] + 1;
                    
                    foreach ($fieldErrors as $error) {
                        $errorMessage = "In record {$index}: {$error}";
                        $errors[] = $errorMessage;
                    }
                }
            
                $errorMessage = implode("<br>", $errors);
                throw ValidationException::withMessages(['validation_error' => $errorMessage]);
            }

            // if ($validator->fails()) { //if there are any validation errors.
            //     $errors = $validator->errors()->all();
            //     throw ValidationException::withMessages(['validation_error' => $errors]);
            // }

            foreach ($dataRows as $row) { //loop through each row in Excel file
                $newItem = new Item();
                $newItem->item_id = $this->itemInterface->getMaxItemId(); //get the max item id
                $newItem->item_code = $row[0];
                $newItem->item_name = $row[1];
                $categoryId = $this->categoryInterface->getCategoryByName($row[2]); //get category id
                $newItem->category_id = $categoryId->id;
                $newItem->safety_stock = $row[3];
                $newItem->received_date = Date::excelToDateTimeObject($row[4])->format('Y-m-d'); //convert date from Excel file to date format
                $newItem->description = $row[5];

                $newItem->save();
            }
        } catch (ValidationException $e) { //if there are any validation errors.
            $errorMessages = $e->validator->errors()->all();
            throw new \Exception(implode(', ', $errorMessages), 400);
        } catch (\Exception $e) { //if there are any other errors.
            throw new \Exception($e->getMessage(), 400);
        }
    }
}
