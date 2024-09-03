<?php

namespace App\Http\Resources\Sight\GetSight;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Sight\CursorSightsResource;
use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetSightResource extends BaseResource
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
            'status'    => 'success',
            'message'   => __('messages.sight.get_sights.success'),
            'sights'    => new CursorSightsResource($this->resource),
        ];
    }
}
