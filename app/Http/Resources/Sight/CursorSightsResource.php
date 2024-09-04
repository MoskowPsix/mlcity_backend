<?php

namespace App\Http\Resources\Sight;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CursorSightsResource extends JsonResource
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
            'data'          => method_exists($this->resource, 'items') ? SightResource::collection($this->items()) : [new SightResource($this->resource)],
            'next_cursor'   => method_exists($this->resource, 'nextCursor') ? $this->nextCursor() ? $this->nextCursor()->encode() : null : null,
            'prev_cursor'   => method_exists($this->resource, 'previousCursor') ? $this->previousCursor() ? $this->previousCursor()->encode() : null : null,
        ];
    }
}
