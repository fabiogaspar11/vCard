<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcardPut extends FormRequest
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
            'name'               => 'nullable|string|max:50',
            'email'              => 'nullable|string|max:255|email',
            'photo_url'          => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
       ];
    }
    public function messages()
    {
        return [
            'name.max' => 'Name cannot have more than 50 characters',
            'email.max' => 'Email cannot have more than 255 characters',
            'photo_url.mimes' => 'The file is not a image',
        ];
    }
}
