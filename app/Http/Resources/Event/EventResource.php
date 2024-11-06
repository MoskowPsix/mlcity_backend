<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\View\ViewCountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $sponsor
 * @property string $date_start
 * @property string $date_end
 * @property string $description
 * @property string $materials
 * @property int $user_id
 * @property int $vk_post_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $age_limit
 * @property int $afisha7_id
 * @property double $distance
 */
class EventResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        // dd($this->resource->toArray());
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'sponsor'           => $this->sponsor,
            'date_start'        => $this->date_start,
            'date_end'          => $this->date_end,
            'description'       => $this->description,
            'materials'         => $this->materials,
            'user_id'           => $this->user_id,
            'vk_post_id'        => $this->vk_post_id,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'age_limit'         => $this->age_limit,
            'afisha7_id'        => $this->afisha7_id,
            'distance'          => $this->when(!empty($this->distance), $this->distance),
            'statuses'          => $this->whenLoaded('statuses'),
            'files'             => $this->whenLoaded('files'),
            'author'            => $this->whenLoaded('author'),
            'types'             => $this->whenLoaded('types'),
            'price'             => $this->whenLoaded('price'),
            'likedUsers'        => $this->whenLoaded('likedUsers'),
            'views'             => $this->whenLoaded('viewCount', new ViewCountResource($this->viewCount)),
            'favoritesUsers'    => $this->whenLoaded('favoritesUsers'),
            'places'            => $this->whenLoaded('places'),
            'places_full' => $this->whenLoaded('placesFull'),
        ];
    }
}
