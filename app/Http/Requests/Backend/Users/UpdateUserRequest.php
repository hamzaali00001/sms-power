<?php

namespace App\Http\Requests\Backend\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'role_id' => 'required|integer|exists:roles,id' . $this->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'mobile' => [
                'required',
                Rule::unique('users')->ignore($this->user->id),
                Rule::phone()->detect()->country('KE')->mobile()
            ],
            'timezone' => 'required|timezone'
        ];

        if (Auth::user()->hasRole('admin')) {
            $rules['credit'] = 'required|integer|min:0';
            $rules['suspended'] = 'required|boolean';
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
            'name.required'  => 'The Full Name is required',
            'email.required'  => 'The User Email is required',
            'mobile.required'  => 'The User Mobile No. is required',
            'credit.required'  => 'The User Credit is required',
            'suspended.required'  => 'The Suspend User is required',
            'timezone.required'  => 'The User Timezone is required',
            'role_id.required' => 'The User Role is required'
        ];
    }
}
