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
            'photo_url'          => 'nullable|string|max:255',
            'password'           => 'required|string|max:255',
            'confirmation_code'  => 'required|string|max:255',
            'blocked'            => 'required|integer|in:0,1',
            'balance'            => ['required', 'numeric', 'min:0.00', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'max_debit'          => ['required', 'numeric', 'regex:/^[0-9]+((.|,)[0-9]{1,2})?$/']
        ];
    }
}
