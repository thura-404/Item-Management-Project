<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ItemDeleteRequest
 * @author Thura Win
 * @create 30/6/2023
 */
class ItemDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @author Thura Win
     * @create 30/6/2023
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @author Thura Win
     * @create 30/6/2023
     * @return array
     */
    public function rules()
    {
        return [
            //
            'txtId' => 'required|numeric|exists:items,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @author Thura Win
     * @create 30/6/2023
     * @return array
     */
    public function messages()
    {
        return [
            'txtId.required' => 'Id is missing',
            'txtId.numeric' => 'Id must be numeric',
            'txtId.exists' => 'Item not found',
        ];
    }
}
