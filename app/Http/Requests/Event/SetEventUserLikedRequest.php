<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class SetEventUserLikedRequest extends FormRequest
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
            "event_id" => "required|integer|min:1"
        ];
    }

    public function messages()
    {
        return[
            "event_id.required" => "A event_id is required",
            "event_id.min" => "A event_id must be more than 0"
        ];
    }
}
