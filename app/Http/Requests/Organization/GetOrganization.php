<?php

namespace App\Http\Requests\Organization;

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
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "string|min:3",
            "inn" => "string|min:9|max:10",
            "ogrn" => "string|min:12|max:13",
            "kpp" => "string|min:8|max:9",
            "user_id" => "integer",
            "address" => "string",
            "number" => "string|min:0|max:10",
            "description" => "string"
        ];
    }
}
