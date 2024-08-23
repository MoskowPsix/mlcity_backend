<?php

namespace App\Http\Resources\Organization\getUserOrganizations;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Organization\OrganizationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GetUserOrganizationsOrganizationSuccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'status'         => 'success',
            'message'        => __('messages.organization.get_user_organizations.success'),
            'organizations'  => $this->resource,
        ];
    }
}
