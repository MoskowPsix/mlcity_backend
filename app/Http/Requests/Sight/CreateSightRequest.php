<?php

namespace App\Http\Requests\Sight;

use Illuminate\Foundation\Http\FormRequest;

class CreateSightRequest extends FormRequest
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
            'name'          => 'required|min:3',
            'coords'        => 'required|min:2',
            'description'   => 'nullable|string',
            'type'          => 'required',
            'status'        => 'required',
            'locationId'    => 'required|exists:locations,id',
            'vkGroupId'     => 'nullable|string',
            'vkPostId'      => 'nullable|string',
            'phone_number'  => 'nullable|string',
            'email'         => 'nullable|email',
            'site'          => 'nullable|string',
            'localFilesImg' => 'nullable',
            'localFilesImg.*' => 'nullable|mimes:jpeg,jpg,png',
        ];
    }
}
