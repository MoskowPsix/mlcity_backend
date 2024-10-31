<?php

namespace App\Http\Requests\Sight;

use Illuminate\Foundation\Http\FormRequest;

class GetSightsRequest extends FormRequest
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
            'pagination'        => 'nullable|string|in:true',
            'page'              => 'nullable|string|min:1',
            'limit'             => 'nullable|integer|min:1|max:50',
            'order'             => 'nullable|string',
            'userId'            => 'nullable|integer|exists:users,id',
            'likedUser'         => 'nullable|string|in:true',
            'name'              => 'nullable|string|min:3|max:255',
            'sponsor'           => 'nullable|string|min:3|max:255',
            'favoriteUser'      => 'nullable|string|in:true',
            'statusLast'        => 'nullable|string|in:true',
            'statuses'          => 'nullable|string',
            'locationId'        => 'nullable|integer|exists:locations,id',
            'address'           => 'nullable|string|min:3|max:255',
            'sightTypes'        => 'nullable|string',
            'user'              => 'nullable|string|min:3|max:255',
            'radius'            => 'nullable|integer|min:1|max:25',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'searchText'        => 'nullable|string|min:3',
            'sightIds'          => 'nullable|string',
            'latitude_position' => 'nullable|numeric|between:-87,89',
            'longitude_position' => 'nullable|numeric|between:-87,89',
        ];
    }
}
