<?php

namespace App\Http\Requests\HistoryContent;

use Illuminate\Foundation\Http\FormRequest;

class GetLimitPageRequest extends FormRequest
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
            "limit" => "nullable|integer|min:0|max:50",
            "page" =>"nullable|string|max:255",
        ];
    }
}
