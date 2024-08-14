<?php

namespace App\Http\Resources\Organization\Store;

use App\Http\Resources\Organization\OrganizationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrganizationSuccessResource extends JsonResource
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
            'status'        => 'success',
            'message'       => __('messages.organization.created.success'),
            'organization'  => new OrganizationResource($this->resource)
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(201);
    }
}
