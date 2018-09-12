<?php

namespace App\Http\Requests\Backend\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => [
                'required',
                'unique:users',
                //Rule::phone()->detect()->country('KE')->mobile()
            ],
            'credit' => 'required|integer',
            'timezone' => 'required|timezone',
            'role_id' => 'required|integer|exists:roles,id',
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
            'name.required'  => 'The Full Name is required',
            'email.required'  => 'The User Email is required',
            'password.required'  => 'The Password is required',
            'mobile.required'  => 'The Mobile No. is required',
            'credit.required'  => 'The SMS Credit is required',
            'timezone.required'  => 'The User Timezone is required',
            'role_id.required' => 'The User Role is required'
        ];
    }
}
