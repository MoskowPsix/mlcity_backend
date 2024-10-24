<?php

namespace App\Http\Resources\Sight\GetSight;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Sight\CursorSightsResource;
use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetSightResource extends BaseResource
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
            'status'    => 'success',
            'message'   => __('messages.sight.get_sights.success'),
            'sights'    => new CursorSightsResource($this->resource),
        ];
    }
}
