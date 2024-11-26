<?php

namespace App\Http\Resources\Event\addView;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'message' => __('messages.event.add_view.success'),
        ];
    }
}
