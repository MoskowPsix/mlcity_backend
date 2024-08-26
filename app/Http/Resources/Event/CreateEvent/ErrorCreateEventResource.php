<?php

namespace App\Http\Resources\Event\CreateEvent;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorCreateEventResource extends JsonResource
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
            'status'    => 'error',
            'message'   => __('messages.event.create.error'),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(500);
    }
}
