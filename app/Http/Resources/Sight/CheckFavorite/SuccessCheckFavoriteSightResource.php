<?php

namespace App\Http\Resources\Sight\CheckFavorite;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessCheckFavoriteSightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 'success',
            'message' => $this->resource ? __('messages.sight.check_favorite.favorite') : __('messages.sight.check_favorite.not_favorite'),
            'is_favorite' => $this->resource ? 'true' : 'false',
        ];
    }
}
