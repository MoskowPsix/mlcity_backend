<?php

namespace App\Http\Requests\Integration;

use Illuminate\Foundation\Http\FormRequest;

class MototrackCreateSightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $lat = $this->input('latitude');
        $lng = $this->input('longitude');
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return;
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        // Частый баг синка: lat/lng перепутаны (Москва как 37.57 / 55.75)
        if ($lat > -20 && $lat < 45 && $lng > 40 && $lng < 85) {
            $this->merge([
                'latitude' => $lng,
                'longitude' => $lat,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'sourceId' => 'required|string|max:255',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'required|string|url|max:5000',
        ];
    }
}
