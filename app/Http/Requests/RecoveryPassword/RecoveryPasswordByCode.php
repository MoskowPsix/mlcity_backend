<?php

namespace App\Http\Requests\RecoveryPassword;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $code
 * @property string $password
 */
class RecoveryPasswordByCode extends FormRequest
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
            'code'                      => 'required|string',
            'password'                  => 'required|min:8',
            'password_confirmation'     => 'required|same:password',
        ];
    }
}
