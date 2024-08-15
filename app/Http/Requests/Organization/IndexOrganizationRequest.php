<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class IndexOrganizationRequest extends FormRequest
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
            'name'              => 'nullable|string|min:3',
            'organization_id'   => 'nullable|integer|exists:organizations,id',
            'inn'               => 'nullable|string|min:9|max:10',
            'kpp'               => 'nullable|string|min:8|max:9',
            'ogrn'              => 'nullable|string|min:12|max:13',
            'description'       => 'nullable|string',
            'number'            => 'nullable|string|min:9|max:10',
            'user'              => 'boolean',
        ];
    }
}
