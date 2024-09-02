<?php

namespace App\Http\Resources\Event\GetEventForAuthor;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetEventForAuthorResource extends JsonResource
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
            'message'   => __('messages.event.get_events_for_author.success'),
            'events'    => $this->resource,
        ];
    }
}
