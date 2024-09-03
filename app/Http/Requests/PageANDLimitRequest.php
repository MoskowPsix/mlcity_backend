<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $limit
 * @property string $page
 */
class PageANDLimitRequest extends FormRequest
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
            'page' => "nullable",
            "limit" => "integer|max:50"
        ];
    }

    public function messages()
    {
        return[
            "limit.max" => "A limit must be less 50"
        ];

    }
}
