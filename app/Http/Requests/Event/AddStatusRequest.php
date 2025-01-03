<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class AddStatusRequest extends FormRequest
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
            "status_id" => 'required|integer|exists:statuses,id'
        ];
    }
}
