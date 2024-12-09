<?php

namespace App\Http\Resources\Event\GetEventUserLikedIds;

use App\Http\Resources\Event\CursorEventResource;
use App\Http\Resources\User\CursorUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetEventUserLikedIdsResource extends JsonResource
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
            'status'    => 'success',
            'message'   => __('messages.event.event_user_liked_ids.success'),
            'events'    => new CursorUserResource($this->resource),
        ];
    }
}
