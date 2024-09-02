<?php

namespace App\Http\Resources\Event\CreateEvent;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessCreateEventResource extends JsonResource
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
            'message'   => __('messages.event.create.success'),
        ];
    }
}
