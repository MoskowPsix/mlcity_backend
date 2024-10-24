<?php

namespace App\Http\Resources\Sight\CreateSight;

use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessCreateSightResource extends JsonResource
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
            'status' =>'success',
            'message' => __('messages.sight.create.success'),
            'sight' => new SightResource($this->resource)
        ];
    }
}
