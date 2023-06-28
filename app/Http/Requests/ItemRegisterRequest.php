<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CategoryAddRequest
 * @author Thura Win
 * @create 23/06/2023
 */
class ItemRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @author Thura Win
     * @create 23/06/2023
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function rules()
    {
        return [
            //
            'txtItemID' => 'required',
            'txtCode' => 'required',
            'txtName' => 'required',
            'txtStock' => 'required',
            'txtDate' => 'required',
            'cbocategories' => 'required|exists:categories,id',
            'filImage' => 'mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @author Thura Win
     * @create 23/06/2023
     * @return array
     */
    public function messages()
    {

        return [
            'txtItemID.required' => 'Item ID is Required',
            'txtCode.required' => 'Code is Required',
            'txtName.required' => 'Name is Required',
            'txtStock.required' => 'Stock is Required',
            'txtDate.required' => 'Date is Required',
            'cbocategories.required' => 'Category is Required',
            'cbocategories.exists' => 'Category is Invalid',
            'filImage.mimes' => 'Image must be an image',
            'filImage.max' => 'Image must be less than 2MB'
        ];
    }
}
