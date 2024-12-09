<?php

namespace App\Http\Resources\Event\GetOrganizatonOfEvent;

use App\Http\Resources\Organization\OrganizationResource;
use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetOrganizationOfEventResource extends JsonResource
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
            'status' => 'success',
            'message' => __('messages.event.get_organization_of_event.success'),
            'organization' => new SightResource($this->resource),
        ];
    }
}
