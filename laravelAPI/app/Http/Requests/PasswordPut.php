<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class PasswordPut extends FormRequest
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
            'old_password' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ];
    }
    public function messages()
    {
        return [
            'old_password.required' => 'Old password is mandatory',
            'password.required' => 'New Password is mandatory'
        ];
    }

}
