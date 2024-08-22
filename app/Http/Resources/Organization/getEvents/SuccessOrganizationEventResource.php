<?php

namespace App\Http\Resources\Organization\getEvents;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessOrganizationEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "events" => ""
        ];
    }
}
