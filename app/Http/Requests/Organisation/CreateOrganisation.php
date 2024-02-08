<?php

namespace App\Http\Requests\Organisation;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrganisation extends FormRequest
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
            "name" => "require|string|min:3",
            "inn" => "require|string|min:9|max:10",
            "ogrn" => "require|string|min:12|max:13",
            "kpp" => "require|string|min:8|max:9",
            "user_id" => "require|integer",
            "address" => "require|string",
            "number" => "require|integer|min:9|max:10",
            "description" => "require|string"
        ];
    }
}
