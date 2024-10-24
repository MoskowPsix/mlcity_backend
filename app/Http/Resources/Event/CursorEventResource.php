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
    public function toArray($request): array
    {
        return [
            'data'          => method_exists($this->resource, 'items') ? EventResource::collection($this->items()) : [new EventResource($this->resource)],
            'next_cursor'   => method_exists($this->resource, 'nextCursor') ? $this->nextCursor() ? $this->nextCursor()->encode() : null :null,
            'prev_cursor'   => method_exists($this->resource, 'previousCursor') ? $this->previousCursor() ? $this->previousCursor()->encode(): null : null,
        ];
    }
}
