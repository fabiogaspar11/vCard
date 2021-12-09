<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmationCodePut extends FormRequest
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
            'confirmation_code' => 'required|integer|digits:4',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Old password is mandatory',
            'confirmation_code.required' => 'PIN is mandatory',
            'confirmation_code.digits' => 'PIN must have 4 digits',
        ];
    }
}
