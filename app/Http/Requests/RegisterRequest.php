<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'                   => 'required|min:3|max:50|unique:users',
            'email'                  => 'required|email|unique:users',
            'password'               => 'required|min:8',
            'password_confirmation'  => 'required|same:password',
            'number'                 => 'min:10|max:10|unique:users',
        ];
    }
}
