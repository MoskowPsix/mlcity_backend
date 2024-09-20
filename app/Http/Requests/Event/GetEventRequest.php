<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node\Expr\FuncCall;

class GetEventRequest extends FormRequest
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
     *@return array<string, mixed>
     */
    public function rules()
    {
        return [
            "address"       => "nullable|string|min:3",
            "sponsor"       => "nullable|string|min:3",
            "user_name"     => "nullable|string|min:3",
            "user_email"    => "nullable|string|min:3",
            "page"          => "nullable|string",
            "limit"         => "nullable|integer|min:1|max:50",
            "name"          => "nullable|string|min:3",
            "userId"        => "nullable|integer",
            "likedUser"     => "nullable|string",
            "favoriteUser"  => "nullable|string",
            "statuses"      => "nullable|string",
            "statusLast"    => "nullable|boolean",
            "locationId"    => "nullable|string",
            "dateStart"     => "nullable|string",
            "dateEnd"       => "nullable|string",
            "eventTypes"    => "nullable|string|integer",
            "radius"        => "nullable|integer|max:25",
            "latitude"      => "nullable|numeric|between:-87,89",
            "longitude"     => "nullable|numeric|between:-180,180",
            "searchText"    => "nullable|string|min:3",
            "user"          => "nullable|string|min:3"

        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
