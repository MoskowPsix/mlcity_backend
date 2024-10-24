<?php

namespace App\Http\Resources\Event\CreateEvent;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorAuthCreateEventResource extends JsonResource
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
            'status' => 'error',
            'message' => 'Is not user organization'];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
    }
}
