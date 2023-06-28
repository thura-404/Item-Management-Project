<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CategoryAddRequest
 * @author Thura Win
 * @create 23/06/2023
 */
class CategoryAddRequest extends FormRequest
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
            'name' => 'required|max:255|unique:categories,name'
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
            'name.required' => 'The Category name is required',
            'name.max' => 'The Category name must be less than 255 characters',
            'name.unique' => 'The Category name already exists'
        ];
    }
}
