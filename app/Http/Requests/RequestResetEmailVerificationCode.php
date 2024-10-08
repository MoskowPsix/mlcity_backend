<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestResetEmailVerificationCode extends FormRequest
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
            'code'                   => 'required|min:4|max:4',
            'email'                  => 'required',
                                        'email',
                                        Rule::unique('users')->where(function ($query) {
                                            return $query->whereNotNull('email_verified_at');
                                        }),
        ];
    }
}
