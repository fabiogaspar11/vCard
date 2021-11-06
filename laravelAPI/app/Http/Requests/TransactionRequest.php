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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vcard' => 'required|string|max:9|',
            'datetime' => 'required|string',
            'date' => 'required|date',
            'type' => 'required|digits:1|in:C,D|min:1|max:1',
            'datetime' => 'required',
            'value' => ['required','max:12','min:0.01','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'old_balance' => ['required','max:12','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'new_balance' => ['required','max:12','regex:/^[0-9]+((.|,)[0-9]{1,2})?$/'],
            'payment_type' => 'required|string|in:MBWAY,PAYPAL,IBAN,MB,VISA,max:20',
            'payment_reference' => 'required',
            'pair_transaction' => 'required|integer',
            'pair_vcard' => 'nullable|string|max:9',
            'category_id' => 'required|integer',
            'description' => 'nullable|string|max:255'
        ];
    }
}
