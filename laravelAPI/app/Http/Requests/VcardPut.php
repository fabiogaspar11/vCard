<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcardPut extends FormRequest
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
            'name'               => 'nullable|string|max:50',
            'email'              => 'nullable|string|max:255|email',
            'photo_url'          => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'old_password'       => 'nullable|string|max:255',
            'password'           => 'nullable|string|max:255',
            'confirmation_code'  => 'nullable|integer|digits:4',
            'blocked'            => 'nullable|integer|in:0,1',
            'balance'            => ['nullable', 'numeric', 'min:0.00', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'max_debit'          => ['nullable', 'numeric', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/']
        ];
    }
    public function messages()
    {
        return [
            'name.max' => 'Name cannot have more than 50 characters',
            'email.max' => 'Email cannot have more than 255 characters',
            'photo_url.mimes' => 'The file is not a image',
            'password.max' => 'Password cannot have more than 255 characters',
            'confirmation_code.integer' => 'PIN can only have numbers',
            'confirmation_code.digits' => 'PIN must have 4 digits',
        ];
    }
}
