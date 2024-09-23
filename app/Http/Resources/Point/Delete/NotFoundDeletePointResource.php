<?php

namespace App\Http\Resources\Point\Delete;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotFoundDeletePointResource extends JsonResource
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
            'status' => 'error',
            'message' => __('messages.point.delete.not_found'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(400);
    }
}
