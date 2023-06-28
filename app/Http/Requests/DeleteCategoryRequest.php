<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CategoryAddRequest
 * @author Thura Win
 * @create 23/06/2023
 */
class DeleteCategoryRequest extends FormRequest
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
            'id' => 'required|numeric'
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
            'id.required' => 'id is required',
            'id.numeric' => 'id must be a number'
        ];
    }
}
