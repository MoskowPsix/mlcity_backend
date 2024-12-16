<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\Sight\SightResource;
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
 * @property int $liked_users_count
 * @property int $favorites_users_count
 * @property int $viewCount
 * @property int $min_cult_id
 * @property int $cult_id
 * @property array $places
 */
class EventResource extends JsonResource
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
            'min_cult_id'       => $this->min_cult_id,
            'cult_id'           => $this->cult_id,
            'distance'          => $this->when(!empty($this->distance), $this->distance),
            'statuses'          => $this->whenLoaded('statuses'),
            'files'             => $this->whenLoaded('files'),
            'author'            => $this->whenLoaded('author'),
            'types'             => $this->whenLoaded('types'),
            'price'             => $this->whenLoaded('price'),
            'liked_users_exists'=> $this->liked_users_exists,
            //            'likedUsers'        => $this->whenLoaded('likedUsers'),
            'likedUsers'        => $this->when(!empty($this->liked_users_count), $this->liked_users_count),
            'likedUsers'        => $this->when(isset($this->likes), $this->likes->local_count ?? null),
//            'favoritesUsers'    => $this->favorites_users_count,
            'favoritesUsers'    => $this->when(!empty($this->favorites_users_count), $this->favorites_users_count, 0),
            'views'             => $this->whenLoaded('viewCount', new ViewCountResource($this->viewCount)),
//            'favoritesUsers'    => $this->whenLoaded('favoritesUsers'),
            'places_full'       => $this->whenLoaded('places'),
            'places_full'       => $this->whenLoaded('placesFull'),
            'organization'      => $this->whenLoaded('organization'),
            'location_name'     => $this->when(!empty($this->location_name), $this->location_name),
            'place'             => $this->whenLoaded('place'),
//            'organization' => $this->when(!empty($this->organizationSight), new SightResource($this->organizationSight)),
        ];
    }
}
