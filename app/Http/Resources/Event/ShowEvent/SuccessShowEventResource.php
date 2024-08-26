<?php

namespace App\Http\Resources\Event\ShowEvent;

use App\Http\Resources\Event\EventResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessShowEventResource extends JsonResource
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
            'message'   => __('messages.event.show.success'),
            'event'     => new EventResource($this->resource),
        ];
    }
}
