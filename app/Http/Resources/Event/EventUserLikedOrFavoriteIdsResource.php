<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array $users
 */
class EventUserLikedOrFavoriteIdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['status' => 'success',
                'events' => $this->resource,
            ];
    }
}
