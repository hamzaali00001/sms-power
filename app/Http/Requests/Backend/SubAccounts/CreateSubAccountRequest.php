<?php

namespace App\Http\Requests\Backend\SubAccounts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateSubAccountRequest extends FormRequest
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
            'parent_id' => 'required|integer|exists:users,id',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => [
                'required',
                'unique:users',
                //Rule::phone()->detect()->country('KE')->mobile()
            ],
            'credit' => 'required|integer|min:0',
            'timezone' => 'required|timezone'
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
            'parent_id.required' => 'The Parent A/c is required',
            'name.required'  => 'The Full Name is required',
            'email.required'  => 'The User Email is required',
            'mobile.required'  => 'The Mobile No. is required',
            'credit.required'  => 'The SMS Credit is required',
            'timezone.required'  => 'The User Timezone is required'
        ];
    }
}
