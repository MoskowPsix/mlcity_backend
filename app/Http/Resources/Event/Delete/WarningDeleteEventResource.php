<?php

namespace App\Http\Resources\Event\Delete;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarningDeleteEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status' => 'success',
            'message' => __('messages.event.delete.error'),
        ];;
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(400);
    }
}
