<?php

namespace App\Http\Requests\Backend\SenderIDs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateSenderIDRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'cost' => 'required|integer'
        ];

        if (!Auth::user()->hasRole('admin')) {
            $rules['trans_code'] = 'required|string|max:255';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'  => 'The Sender ID Name is required',
            'cost.required'  => 'The Cost Price is required',
            'trans_code.required'  => 'The Transaction Code is required'
        ];
    }
}
