<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    public function rules(): array
    {
        return [
            'name'                   => [
                                            'required',
                                            'min:3',
                                            'max:50',
                                            Rule::unique('users')->where(function ($query) {
                                                return $query->whereNotNull('email_verified_at');
                                            }),
            ],
            'email'                  => [
                                            'required',
                                            'email',
                                            Rule::unique('users')->where(function ($query) {
                                                return $query->whereNotNull('email_verified_at');
                                            }),
                                        ],
            'password'               => 'required|min:8',
            'password_confirmation'  => 'required|same:password',
            'avatar'                 => 'nullable'
        ];
    }
}
