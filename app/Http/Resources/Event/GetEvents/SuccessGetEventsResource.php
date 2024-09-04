<?php

namespace App\Http\Resources\Event\GetEvents;

use App\Http\Resources\Event\CursorEventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetEventsResource extends JsonResource
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
            'message'   => __('messages.event.get_events.success'),
            'events'    => new CursorEventResource($this->resource),
        ];
    }
}
