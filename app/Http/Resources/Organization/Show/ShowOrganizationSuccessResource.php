<?php

namespace App\Http\Resources\Organization\Show;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Organization\OrganizationResource;

class ShowOrganizationSuccessResource extends JsonResource
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
            'message'       => __('messages.organization.show.success'),
            'organization'  => new OrganizationResource($this->resource)
        ];
    }
}
