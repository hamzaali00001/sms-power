<?php

namespace App\Http\Requests\Backend\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class BuyCreditRequest extends FormRequest
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
            'quantity' => 'required|integer',
            'amount' => 'required',
            'trans_code' => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quantity.required'  => 'The SMS Quantity is required',
            'amount.required'  => 'The SMS Total Cost is required',
            'trans_code.required'  => 'The Transaction Code is required',
        ];
    }
}
