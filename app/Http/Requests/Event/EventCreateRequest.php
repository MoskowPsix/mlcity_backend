<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends FormRequest
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
            'name'                          => 'required|min:3',
            'sponsor'                       => 'required|string',
            'description'                   => 'nullable|min:10',
            'dateStart'                     => 'required|date',
            'dateEnd'                       => 'required|date|after_or_equal:dateStart',
            'type'                          => 'required|string',
            'status'                        => 'required',
            'places'                        => 'nullable|array',
            'prices'                        => 'nullable|array',
            'ageLimit'                      => 'nullable|integer|min:0|max:18',
            'places.*.seances.*.dateStart'  =>  'required|date',
            'places.*.seances.*.dateEnd'    =>  'required|date',

        ];
    }

    public function messages()
    {
        return [
            "name.required" => "A name is required",
            "name.min" => "A name length is must be more than 3",

            "sponsor.required" => "A sponsor is required",
            "sponsor.min" => "A sponsor length is must be more than 3",

            "description.required" => "A description is required",
            "description.min" => "A description length is must be more than 10",

            "dateStart.required" => "A date is required",
            "dateEnd.required" => "A dateEnd is required",
            "type.required" => "A type is required",
            "status.required" => "A status is required"
        ];
    }
}
