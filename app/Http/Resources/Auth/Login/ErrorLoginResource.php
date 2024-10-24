<?php

namespace App\Http\Resources\Auth\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorLoginResource extends JsonResource
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
            'status' => 'error',
            'message' => __('messages.login.error')
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
    }
}
