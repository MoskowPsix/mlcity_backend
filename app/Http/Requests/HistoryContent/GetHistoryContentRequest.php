<?php

namespace App\Http\Requests\HistoryContent;

use Illuminate\Foundation\Http\FormRequest;

class GetHistoryContentRequest extends FormRequest
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
            "name" => "string|min:3",
            "dateStart" => "date",
            "dateEnd" => "date",
            "sponsor" => "string",
            "searchText" => "string"

        ];
    }
}
