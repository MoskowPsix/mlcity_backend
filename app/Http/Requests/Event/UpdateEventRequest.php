<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'name'         => 'nullable|min:3',
            'sponsor'      => 'nullable|min:3',
            'location_id'  => "nullable",
            "address"      => "nullable",
            'latitude'     =>"nullable",
            'longitude'    => "nullable",
            'price'        =>"nullable",
            'materials'    => "nullable",
            'description'  => 'nullable|min:10',
            'dateStart'    => 'nullable|date',
            'dateEnd'      => 'nullable|date|after:date_start',
            'type'         => 'nullable',
            'status'       => 'nullable',
        ];
    }
}
