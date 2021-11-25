<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPut extends FormRequest
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
            'category_id' => 'nullable|integer|exists:categories,id',
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [

            'category_id.integer' => 'Category id must be a integer',
            'category_id.exists' => 'There is no category with this id',

            'description.string' => 'Description must be a string',
            'description.max' => 'Payment reference cannot have more than 255 characters',
        ];
    }
}
