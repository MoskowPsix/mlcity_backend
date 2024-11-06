<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchContentForTextRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'text'      => 'required|min:3|max:255|string',
            'columns'   => 'required|array|min:1|max:10',
            'columns.*' => 'required|string|in:name,description',
//            'dateStart' => 'nullable|date',
//            'dateEnd' => 'nullable|date',
            'page' => 'nullable|string',
            'limit' => 'nullable|integer|max:50'
        ];
    }
}
