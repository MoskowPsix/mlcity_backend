<?php

namespace App\Http\Resources\Point\GetPoint;

use App\Http\Resources\Point\CursorePointResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetPointsResource extends JsonResource
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
            'status' => 'success',
            'message' => __('messages.point.get.success'),
            'points' => new CursorePointResource($this->resource),
        ];
    }
}
