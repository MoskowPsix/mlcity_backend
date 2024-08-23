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
            'description'       => 'nullable|string',
            'user'              => 'boolean',
            'page'              => 'nullable|string',
            'limit'             => 'nullable|integer',
        ];
    }
}
