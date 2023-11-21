<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserChangePasswordRequest extends FormRequest
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
            'password' => 'required|string|min:8|confirmed|alpha_spaces',
            'password_confirmation' => 'required|string|min:8|alpha_spaces',
        ];
    }
}
