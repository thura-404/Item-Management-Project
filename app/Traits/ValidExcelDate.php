<?php
namespace App\Traits;

use Illuminate\Contracts\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ValidExcelDate implements Rule
{
    public function passes($attribute, $value)
    {
        try {
            // Convert the value to a DateTime object using Excel's internal date format
            $date = Date::excelToDateTimeObject($value);
            
            // Check if the converted date is valid
            return $date !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return 'The :attribute must be a valid date.';
    }
}
