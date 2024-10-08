<?php

namespace App\Http\Resources\Auth\Logout;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailedLogoutResource extends JsonResource
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
            'message' => __('messages.logout.error')
        ];
    }
    public function withResponse($request, $response): void
    {
        $response->setStatusCode(500);
    }
}
