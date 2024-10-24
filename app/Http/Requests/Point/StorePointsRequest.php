<?php

namespace App\Http\Requests\Point;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $latitude
 * @property string $longitude
 */
class StorePointsRequest extends FormRequest
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
            'latitude'      => 'required|numeric|between:-87,89',
            'longitude'     => 'required|numeric|between:-180,180',
        ];
    }
}
