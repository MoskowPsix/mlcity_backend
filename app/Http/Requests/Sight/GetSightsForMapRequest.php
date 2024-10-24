<?php

namespace App\Http\Requests\Sight;

use Illuminate\Foundation\Http\FormRequest;

class GetSightsForMapRequest extends FormRequest
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
            'statusLast'        => 'nullable|string|in:true',
            'statuses'          => 'nullable|string',
            'locationId'        => 'nullable|integer|exists:locations,id',
            'address'           => 'nullable|string|min:3|max:255',
            'sightTypes'        => 'nullable|string',
            'radius'            => 'required|integer|min:1|max:25',
            'latitude'          => 'nullable|numeric|between:-87,89',
            'longitude'         => 'nullable|numeric|between:-180,180',
        ];
    }
}
