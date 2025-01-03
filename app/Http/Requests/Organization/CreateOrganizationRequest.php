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
            "name"          => "required|string|min:3|unique:organizations,name",
            "avatar"        => "nullable|file|image|mimes:jpeg,png,jpg|",
            "typeId"        => "required|int|exists:stypes,id",
            "locationId"    => "required|int|exists:locations,id",
            "description"   => "nullable|string"
        ];
    }
}
