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
            'phone_number'       => 'nullable|integer|digits:9|unique:vcards,phone_number',
            'name'               => 'required|string|max:50',
            'email'              => ['required', 'string', 'max:255'], //'regex:/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/'],
            'photo_url'          => 'nullable|image',
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
            'phone_number.unique' => 'A vcard is already associated with this phone number',
            'phone_number.integer' => 'Phone number can only have numbers',
            'phone_number.digits' => 'Phone number must have 9 digits',
            'name.required' => 'Name is mandatory',
            'name.max' => 'Name cannot have more than 50 characters',
            'email.required' => 'Email is mandatory',
            'email.max' => 'Email cannot have more than 255 characters',
            'photo_url.image' => 'The file is not a image',
            'password.required' => 'Password is mandatory',
            'password.max' => 'Password cannot have more than 255 characters',
            'confirmation_code.required' => 'Confirmation code is mandatory',
            'confirmation_code.integer' => 'Confirmation code can only have numbers',
            'confirmation_code.digits' => 'Confirmation code must have 4 digits',
        ];
    }
}
