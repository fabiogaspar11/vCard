<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryPost extends FormRequest
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
            'vcard' => ['required','integer','exists:vcards,phone_number','digits:9','regex:/^(9[0-9])([0-9]{7})?$/'],
            'type' => 'required|string|in:C,D',
            'name' => 'required|string|max:50'
        ];
    }


    public function messages()
    {
        return [
            'vcard.integer' => 'Vcard can only have numbers',
            'vcard.digits' => 'Vcard must have 9 digits',
            'vcard.regex' => 'Vcard must start with number 9',
            'name.required' => 'Name is mandatory',
            'name.max' => 'Name cannot have more than 50 characters',
            'type.required' => 'Type is mandatory',
            'type.in' => 'Type must be "C" or "D"'
        ];
    }
}
