<?php

namespace App\Http\Resources\Organization\Store;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrganizationNoAuthResource extends JsonResource
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
            'status'  => 'error',
            'message' => __('messages.organization.store.no_auth'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
    }
}
