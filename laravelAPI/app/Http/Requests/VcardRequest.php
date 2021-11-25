<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcardRequest extends FormRequest
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
            'phone_number'       => ['nullable','integer','digits:9','unique:vcards,phone_number','regex:/^(9[0-9])([0-9]{7})?$/'],
            'name'               => 'required|string|max:50',
            'email'              => 'required|string|max:255|email',
            'photo_url'          => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'password'           => 'required|string|max:255',
            'confirmation_code'  => 'required|integer|digits:4',
            'blocked'            => 'nullable|integer|in:0,1',
            'balance'            => ['nullable', 'numeric', 'min:0.00', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'max_debit'          => ['nullable', 'numeric', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/']
        ];
    }
        public function messages()
        {
            return [
            'phone_number.unique' => 'Phone number already exists - Duplicated vcard',
            'phone_number.integer' => 'Phone number can only have numbers',
            'phone_number.digits' => 'Phone number must have 9 digits',
            'phone_number.regex' => 'Phone number must start with number 9',
            'name.required' => 'Name is mandatory',
            'name.max' => 'Name cannot have more than 50 characters',
            'email.required' => 'Email is mandatory',
            'email.max' => 'Email cannot have more than 255 characters',
            'photo_url.mimes' => 'The file is not a image',
            'password.required' => 'Password is mandatory',
            'password.max' => 'Password cannot have more than 255 characters',
            'confirmation_code.required' => 'PIN is mandatory',
            'confirmation_code.integer' => 'PIN can only have numbers',
            'confirmation_code.digits' => 'PIN must have 4 digits',
        ];
    }
}
