<?php

namespace App\Http\Resources\Sight\GetEventsInSight;

use App\Http\Resources\Event\CursorEventResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property object $resource
 */
class SuccessGetEventsInSightsResource extends JsonResource
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
            'message'   => __('sight.get_events_in_sight.success'),
            'events'    => new CursorEventResource($this->resource),
        ];
    }
}
