<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CursorEventResource extends JsonResource
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
            'data'          => method_exists($this, 'items') ? EventResource::collection($this->items()) : new EventResource($this->resource),
            'next_cursor'   => method_exists($this, 'nextCursor') ? $this->nextCursor()->encode(): null,
            'prev_cursor'   => method_exists($this, 'previousCursor') ? $this->previousCursor()->encode() : null,
        ];
    }
}
