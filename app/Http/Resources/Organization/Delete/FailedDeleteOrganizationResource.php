<?php

namespace App\Http\Resources\Organization\Delete;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailedDeleteOrganizationResource extends JsonResource
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
            'status'    => 'success',
            'message'   => __('messages.organization.delete.error'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(400);
    }
}