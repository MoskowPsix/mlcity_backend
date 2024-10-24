<?php

namespace App\Http\Resources\Sight\GetSightForMap;

use App\Http\Resources\Sight\CursorSightsResource;
use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetSightsForMapResource extends JsonResource
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
            'message' => __('messages.sight.get_sights_for_map.success'),
            'sights' => SightResource::collection($this->resource),
        ];
    }
}
