<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class CursorEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => EventResource::collection($this->items()),
            'next_cursor' => !empty($this->nextCursor()) ? $this->nextCursor()->encode(): "",
            'prev_cursor' => !empty($this->previousCursor()) ? $this->previousCursor()->encode() : "",
        ];
    }
}
