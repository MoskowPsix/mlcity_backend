<?php

namespace App\Http\Resources\Event\ShowForMapEvent;

use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessShowForMapEventResource extends JsonResource
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
            'message'   => __('messages.event.show_for_map.success'),
            'event'     => new EventResource($this->resource),
        ];
    }
}
