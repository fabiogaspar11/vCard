<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcardDelete extends FormRequest
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
            'password'           => 'required|string|max:255',
            'confirmation_code'  => 'required|integer|digits:4'
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'Password is mandatory',
            'password.max' => 'Password cannot have more than 255 characters',
            'confirmation_code.required' => 'PIN is mandatory',
            'confirmation_code.integer' => 'PIN can only have numbers',
            'confirmation_code.digits' => 'PIN must have 4 digits'
        ];
    }
}
