<?php

namespace App\Http\Resources\Sight\Show;

use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessShowSightResource extends JsonResource
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
            'message'   => __('sight.show.success'),
            'sight'     => new SightResource($this->resource)
        ];
    }
}
