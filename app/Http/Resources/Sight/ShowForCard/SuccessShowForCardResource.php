<?php

namespace App\Http\Resources\Sight\ShowForCard;

use App\Http\Resources\Sight\SightResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessShowForCardResource extends JsonResource
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
            'message'   => __('messages.sight.show_for_card.success'),
            'sight'     => new SightResource($this->resource),
        ];
    }
}
