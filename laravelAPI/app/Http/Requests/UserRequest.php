<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
        'name.required' => 'Name is mandatory',
        'name.max' => 'Name cannot have more than 255 characters',
        'email.required' => 'Email is mandatory',
        'email.max' => 'Email cannot have more than 255 characters',
        'password.required' => 'Password is mandatory',
        'password.max' => 'Password cannot have more than 255 characters',
        ];
    }
}
