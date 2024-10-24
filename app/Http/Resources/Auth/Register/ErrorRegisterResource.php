<?php

namespace App\Http\Resources\Auth\Register;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorRegisterResource extends JsonResource
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
            'message' => __('messages.register.error')
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
    }
}
