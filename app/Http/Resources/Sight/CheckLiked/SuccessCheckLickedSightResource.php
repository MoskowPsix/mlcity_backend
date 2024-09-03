<?php

namespace App\Http\Resources\Sight\CheckLiked;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessCheckLickedSightResource extends JsonResource
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
            'message' => $this->resource ? __('messages.sight.check_liked.liked') : __('messages.sight.check_liked.not_liked'),
            'is_liked' => $this->resource,
        ];
    }
}
