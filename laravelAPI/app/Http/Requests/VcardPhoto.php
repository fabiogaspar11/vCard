<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VcardPost extends FormRequest
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
            'photo_url'          => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
        ];
    }
    public function messages()
    {
        return [
            'photo_url.mimes' => 'The file is not a image',
        ];
    }
}
