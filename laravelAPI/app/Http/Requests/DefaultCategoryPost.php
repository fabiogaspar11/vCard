<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefaultCategoryPost extends FormRequest
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
            'type' => 'required|string|in:C,D',
            'name' => 'required|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is mandatory',
            'name.max' => 'Name cannot have more than 50 characters',
            'type.required' => 'Type is mandatory',
            'type.in' => 'Type must be "C" or "D"'
        ];
    }
}
