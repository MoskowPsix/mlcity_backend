<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventForAuthorReqeust extends FormRequest
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
            'page' => "nullable|string",
            "limit" => "nullable|integer|max:50",
            "statuses"      => "nullable|string",
            "statusLast"    => "nullable|boolean",
        ];
    }

    public function messages()
    {
        return [
            // "page.required" => "A page is reqired",
            "limit.max" => "A limit value must be less or equal than 50"
        ];
    }
}
