<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            "current_password" => 'required|min:6',
            "new_password" => 'required|min:6||confirmed|different:current_password',
            "new_password_confirmation" => 'required|min:6|same:new_password'
        ];
    }
}
