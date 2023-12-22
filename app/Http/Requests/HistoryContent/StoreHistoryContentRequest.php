<?php

namespace App\Http\Requests\HistoryContent;

use Illuminate\Foundation\Http\FormRequest;

class StoreHistoryContentRequest extends FormRequest
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
            "id" => "required|integer",
            "type" =>"required|string",

            "history_content.name" => "string|nullable",
            "history_content.sponsor" => "string|nullable",
            "history_content.descriptions" => "string|nullable",
            "history_content.materials" => "string|nullable",
            "history_content.date_string" => "date|nullable"
        ];
    }
}
