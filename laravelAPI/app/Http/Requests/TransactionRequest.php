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
            'type' => 'required|digits:1|in:C,D|min:1|max:1',
            'date' => 'required',
            'value' => 'required|min:0.01|max:5000',
            'old_balance' => 'required|min:0',
            'new_balance' => 'required|min:0',
            'payment_type' => 'required|in:MBWAY,PAYPAL,IBAN,MB,VISA|'
        ];
    }
}
