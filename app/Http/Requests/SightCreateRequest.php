<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SightCreateRequest extends FormRequest
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
            'name'         => 'required|min:3',
            'sponsor'      => 'required|min:3',
            'coords'       => 'required|min:2',
            'description'  => 'required|min:10',
            'type'         => 'required',
            'status'       => 'required',
        ];
    }
}
