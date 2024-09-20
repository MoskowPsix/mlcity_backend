<?php

namespace App\Http\Resources\Point\StorePoint;

use App\Http\Resources\Point\PointResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessStorePointsResource extends JsonResource
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
            'message' => __('messages.point.store.success'),
            'points' => new PointResource($this->resource),
        ];
    }
}
