<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property bool $likedUser
 */
class SetEventUserLikedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status' => 'success',
            'likedUser' => $this->resource,
            ];
    }
}
