<?php

namespace App\Http\Requests\Backend\Contacts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateContactRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:groups,id',
            'mobile' =>[
                'required',
                Rule::phone()->detect()->country('KE')->mobile(),
                Rule::unique('contacts')->where(function ($query) {
                    return $query->where('group_id', $this->group->id)
                        ->where('user_id', Auth::user()->id);
                })
            ]
        ];
    }
}
