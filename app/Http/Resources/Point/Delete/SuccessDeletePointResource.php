<?php

namespace App\Http\Resources\Point\Delete;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessDeletePointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status' => 'success',
            'message' => __('messages.point.delete.not_found'),
        ];
    }
}
