<?php

namespace App\Http\Resources\Auth\Login;

use Illuminate\Http\Resources\Json\JsonResource;

class NotFoundLoginResource extends JsonResource
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
            'message'   => __('messages.login.not_found')
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(404);
    }
}
