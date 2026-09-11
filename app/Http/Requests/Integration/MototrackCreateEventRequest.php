<?php

namespace App\Http\Requests\Integration;

use Illuminate\Foundation\Http\FormRequest;

class MototrackCreateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sourceId' => 'required|string|max:255',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'trackSourceId' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'required|string|url|max:5000',
            'materials' => 'nullable|string|url|max:2000',
            'prices' => 'nullable|array',
            'prices.*.cost_rub' => 'required|integer|min:0',
            'prices.*.descriptions' => 'nullable|string|max:500',
        ];
    }
}
