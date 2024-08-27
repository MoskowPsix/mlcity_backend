<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'id'               => $this->id,
            'name'              => $this->name,
            'sponsor'           => $this->sponsor,
            'description'       => $this->description,
            'materials'         => $this->materials,
            'user_id'           => $this->user_id,
            'vk_post_id'        => $this->vk_post_id,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'statuses'          => $this->whenLoaded('statuses'),
            'files'             => $this->whenLoaded('files'),
            'author'            => $this->whenLoaded('author'),
            'types'             => $this->whenLoaded('types'),
            'price'             => $this->whenLoaded('price'),
            'likedUsers'        => $this->whenLoaded('likedUsers'),
            'favoritesUsers'    => $this->whenLoaded('favoritesUsers'),
            'places'            => $this->whenLoaded('places'),
        ];
    }
}
