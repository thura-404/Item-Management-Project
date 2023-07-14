<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ItemRequestRequest
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
            'txtItemID' => 'required|max:6',
            'txtCode' => 'required|max:25',
            'txtName' => 'required|max:40',
            'txtStock' => 'required|numeric|max:99999',
            'txtDate' => 'required|date|max:40',
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
            'txtItemID.required' => __('public.itemIdRequired'),
            'txtItemID.max' => __('public.itemIdMax'),
            'txtCode.required' => __('public.itemCodeRequired'),
            'txtCode.max' => __('public.itemCodeMax'),
            'txtName.required' => __('public.itemNameRequired'),
            'txtName.max' => __('public.itemNameMax'),
            'txtStock.required' => __('public.stockRequired'),
            'txtStock.numeric' => __('public.stockMustBeNumber'),
            'txtStock.max' => __('public.stockMustBeLess'),
            'txtDate.required' => __('public.dateIsRequired'),
            'txtDate.date' => __('public.dateIsInvalid'),
            'txtDate.max' => __('public.dateIsInvalid'),
            'cbocategories.required' => __('public.categoryRequired'),
            'cbocategories.exists' => __('public.categoryInvalid'),
            'filImage.mimes' => __('public.imageMustBeImage'),
            'filImage.max' => __('public.imageMustBe2MB')
        ];
    }
}
