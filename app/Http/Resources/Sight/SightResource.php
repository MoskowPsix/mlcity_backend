<?php

namespace App\Http\Resources\Sight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $sponsor
 * @property string $latitude
 * @property string $longitude
 * @property int $location_id
 * @property string $address
 * @property string $description
 * @property string $materials
 * @property int $user_id
 */
class SightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'sponsor'           => $this->sponsor,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'location_id'       => $this->location_id,
            'address'           => $this->address,
            'description'       => $this->description,
            'materials'         => $this->materials,
            'user_id'           => $this->user_id,
            'afisha7_id'        => $this->afisha7_id,
            'types'             => $this->whenLoaded('types'),
            'statuses'          => $this->whenLoaded('statuses'),
            'favoritesUsers'    => $this->whenLoaded('favoritesUsers'),
            'likedUsers'        => $this->whenLoaded('likedUsers'),
            'author'            => $this->whenLoaded('author'),
            'files'             => $this->whenLoaded('files'),
            'likes'             => $this->whenLoaded('likes'),
            'locations'         => $this->whenLoaded('locations'),
            'historyContents'   => $this->whenLoaded('historyContents'),
            'prices'            => $this->whenLoaded('prices'),
            'events'           => $this->whenLoaded('events'),
            'organization'    => $this->whenLoaded('organization')
            ];
    }
}
