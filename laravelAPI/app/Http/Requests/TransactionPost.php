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


        $rulePairVcard  = 'nullable';
        $rulesEachPaymentType = '';
        if($this->payment_type == 'IBAN'){
            $rulesEachPaymentType = 'regex:/^PT[0-9]{23}$/';
        }
        if($this->payment_type == 'MASTERCARD'){
            $rulesEachPaymentType = 'regex:/^5[1-5][0-9]{14}|^(222[1-9]|22[3-9]\\d|2[3-6]\\d{2}|27[0-1]\\d|2720)[0-9]{12}$/';
        }
        if($this->payment_type == 'MB'){
            $rulesEachPaymentType = 'regex:/^[0-9]{5}-[0-9]{9}$/';
        }
        if($this->payment_type == 'MBWAY'){
            $rulesEachPaymentType = 'regex:/^(9[0-9])([0-9]{7})?$/';
        }
        if($this->payment_type == 'PAYPAL'){
            $rulesEachPaymentType = 'regex:/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';
        }
        if($this->payment_type == 'VCARD'){
            $rulesEachPaymentType = 'same:pair_vcard';
            $rulePairVcard = 'required';
        }
        if($this->payment_type == 'VISA'){
            $rulesEachPaymentType = 'regex:/^4[0-9]{12}(?:[0-9]{3})?$/';
        }
        return [
            'vcard' => ['required','integer','exists:vcards,phone_number','digits:9','regex:/^(9[0-9])([0-9]{7})?$/'],
            'type' => ['required', 'string', 'in:C,D'],
            'value' => ['required','numeric','min:0.01','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'payment_type' => ['required', 'string', 'max:10', 'exists:payment_types,code' ],
            'payment_reference' => ['required','max:255',$rulesEachPaymentType],
            'pair_vcard' => [$rulePairVcard,'integer','digits:9','regex:/^(9[0-9])([0-9]{7})?$/','exists:vcards,phone_number'],
            'category_id' => 'nullable|integer','exists:categories,id',
            'description' => 'nullable|string|max:255'
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

            'payment_reference.required' => 'Payment reference is mandatory',
            'payment_reference.string' => 'Payment reference must be a string',
            'payment_reference.max' => 'Payment reference cannot have more than 255 characters',

            'pair_vcard.integer' => 'Phone number of pair vcard must be a integer',
            'pair_vcard.exists' => 'There is no vcard with this phone number',
            'pair_vcard.digits' => 'Pair vcard Phone number must have 9 digits',
            'pair_vcard.regex' => 'Vcard Phone number must start with number 9',
            'pair_vcard.required' => 'Pair vcard is mandatory',

            'category_id.integer' => 'Category id must be a integer',
            'category_id.exists' => 'There is no category with this id',

            'description.string' => 'Description must be a string',
            'description.max' => 'Payment reference cannot have more than 255 characters',
        ];
    }
}
