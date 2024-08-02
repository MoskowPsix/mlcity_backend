<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property bool $chekLiked
 */
class CheckLikedAndFavoriteResource extends JsonResource
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
            'check'  => $this->resource
        ];
    }
}
