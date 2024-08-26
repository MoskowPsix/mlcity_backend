<?php

namespace App\Http\Resources\Event\GetEventUserLikedIds;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetEventUserLikedIdsResource extends JsonResource
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
            'message'   => __('messages.event.event_user_liked_ids.success'),
            'events'    => $this->resource,
        ];
    }
}
