<?php

namespace App\Http\Resources\Event\SetEventUserLiked;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessSetEventUserLikedResource extends JsonResource
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
            'status'    => 'success',
            'likedUser' => $this->resource,
        ];
    }
}
