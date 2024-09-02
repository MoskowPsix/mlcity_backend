<?php

namespace App\Http\Resources\Event\CheckLikedEvent;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessCheckLikedEventLikedResource extends JsonResource
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
            'is_liked'  => $this->resource,
        ];
    }
}
