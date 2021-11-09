<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'vcard' => 'required|integer|digits:9|',
            'datetime' => 'required|date_format:Y-m-d H:i:s',
            'date' => 'required|date',
            'type' => 'required|digits:1|in:C,D|min:1|max:1',
            'value' => ['required','max:12','min:0.01','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'old_balance' => ['required','max:12','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'new_balance' => ['required','max:12','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'payment_type' => 'required|string|max:20',
            'payment_reference' => 'required',
            'pair_transaction' => 'required|integer',
            'pair_vcard' => 'nullable|integer|digits:9|',
            'category_id' => 'required|integer',
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
        'vcard.required' => 'Vcard is mandatory',
        'vcard.digits' => 'Vcard is mandatory',
        'vcard.max' => 'Name cannot have more than 9 numbers',
        ];
    }
}
