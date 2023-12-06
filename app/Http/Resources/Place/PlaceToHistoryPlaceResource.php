<?php

namespace App\Http\Resources\Place;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceToHistoryPlaceResource extends JsonResource
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
            "place_id" => $this->id,
            "sight_id" => $this->sight_id,
            "location_id" => $this->location_id,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "address" => $this->address
        ];
    }
}
