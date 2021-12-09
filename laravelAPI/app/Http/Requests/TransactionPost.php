<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPost extends FormRequest
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
            'type' => ['required', 'string', 'in:C,D'],
            'value' => ['required','numeric','min:0.01','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'payment_type' => ['required', 'string', 'max:10', 'exists:payment_types,code' ],
            'payment_reference' => ['nullable','max:255'],
            'pair_vcard' => ['nullable','integer','digits:9','regex:/^(9[0-9])([0-9]{7})?$/','exists:vcards,phone_number'],
            'category_id' => 'nullable|integer','exists:categories,id',
            'description' => 'nullable|string|max:255',
            'confirmation_code'  => 'required|integer|digits:4',
        ];
    }

    public function messages()
    {
        return [
            'vcard.required' => 'Vcard phone number is mandatory',
            'vcard.digits' => 'Vcard phone number must have 9 digits',
            'vcard.exists' => 'There is no Vcard with this phone number',
            'vcard.regex' => 'Vcard Phone number must start with number 9',

            'type.required' => 'Type is mandatory',
            'type.string' => 'Type must be a string',
            'type.in' => 'Type must be C or D',

            'value.required' => 'Value is mandatory',
            'value.regex' => 'Value is invalid',
            'value.min' => 'Value must be bigger than 0.00',
            'value.numeric' => 'Value must be a number',

            'payment_type.required' => 'Payment type is mandatory',
            'payment_type.string' => 'Payment type must be a string',
            'payment_type.max' => 'Payment type cannot have more than 10 characters',
            'payment_type.exists' => 'Payment type does not exists',

            'payment_reference.string' => 'Payment reference must be a string',
            'payment_reference.max' => 'Payment reference cannot have more than 255 characters',
            'pair_vcard.integer' => 'Phone number of pair vcard must be a integer',
            'pair_vcard.exists' => 'There is no vcard with this phone number',
            'pair_vcard.digits' => 'Pair vcard Phone number must have 9 digits',
            'pair_vcard.regex' => 'Vcard Phone number must start with number 9',

            'category_id.integer' => 'Category id must be a integer',
            'category_id.exists' => 'There is no category with this id',

            'description.string' => 'Description must be a string',
            'description.max' => 'Payment reference cannot have more than 255 characters',

            'confirmation_code.required' => 'PIN is mandatory',
            'confirmation_code.integer' => 'PIN can only have numbers',
            'confirmation_code.digits' => 'PIN must have 4 digits',
        ];
    }
}
