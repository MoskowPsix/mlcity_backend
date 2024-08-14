<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrganizationRequest extends FormRequest
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
            "name" => "required|string|min:3",
            "inn" => "nullable|string|min:9|max:10|unique:organizations",
            "ogrn" => "nullable|string|min:12|max:13|unique:organizations",
            "kpp" => "nullable|string|min:8|max:9|unique:organizations",
            "number" => "nullable|string|min:9|max:10|unique:organizations",
            "description" => "nullable|string"
        ];
    }
}
