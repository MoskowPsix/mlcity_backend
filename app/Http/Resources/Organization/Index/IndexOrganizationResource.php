<?php

namespace App\Http\Resources\Organization\Index;

use App\Http\Resources\Organization\OrganizationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexOrganizationResource extends JsonResource
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
            'message'       => __('messages.organization.index.success'),
            'organizations' => OrganizationResource::collection($this->resource)
        ];
    }
}
