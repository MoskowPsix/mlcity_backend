<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerficationCodeRequest extends FormRequest
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
            'code' => 'required|integer|min:999|max:10000'
        ];
    }
    public function message() {
        return [
            'code|required' => 'Ожидается аргумент code',
            'code|integer' => 'Аргуаент code должен быть числом',
            'code|min:4' => 'Аргуаент code должен состоять из 4 символов',
            'code|max:4' => 'Аргуаент code должен состоять из 4 символов',
        ];
    }
}
