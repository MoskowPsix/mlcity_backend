<?php

namespace App\Http\Resources\Sight\GetSightUserLikedAndFavoriteIds;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetSightUserLikedAndFavoriteResource extends JsonResource
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
            'result' => $this->resource,
            ];
    }
}
