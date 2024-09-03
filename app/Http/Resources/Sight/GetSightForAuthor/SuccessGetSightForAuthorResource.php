<?php

namespace App\Http\Resources\Sight\GetSightForAuthor;

use App\Http\Resources\Sight\CursorSightsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetSightForAuthorResource extends JsonResource
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
            'message' => __('messages.sight.get_sights_for_author.success'),
            'sights' => new CursorSightsResource($this->resource),
        ];
    }
}
