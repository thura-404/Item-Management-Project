<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class checkEmployeeLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'txtId' => 'required|numeric',
            'txtPassword' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'txtId.required' => __('public.enterYourId'),
            'txtId.numeric' => __('public.enterValidId'),
            'txtPassword.required' => __('public.enterPassword')            
        ];
    }
}
