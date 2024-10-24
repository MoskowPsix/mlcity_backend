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
            "name" => "nullable|string|min:3",
            "dateStart"     => "nullable|date",
            "dateEnd"       => "nullable|date",
            "sponsor"       => "nullable|string",
            "searchText"    => "nullable|string",

            "user"          => "nullable|string|min:3",
            "eventTypes"    => "nullable|integer",
            "latitude"      => "nullable|integer",
            "longitude"     => "nullable|integer",
            "statuses"      => "nullable|string",
            "statusLast"    => "nullable|string",
            "page"          => "nullable|string",
            "limit"         => "nullable|integer|min:1|max:50",
            'phone_number'  => "nullable|string",
            'email'         => "nullable|email",
            'site'          => "nullable|string",
        ];
    }
}
