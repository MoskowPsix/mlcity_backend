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
//        dd($this->currentPage());
        if(method_exists($this->resource, 'nextCursor')){
            return $this->getCursorPaginate();
        } else if(method_exists($this->resource, 'perPage')) {
            return $this->getSimplePaginate();
        } else {
            return $this->noPageOut();
        }
    }

    public function getCursorPaginate():array
    {
        return [
            'data'          => method_exists($this->resource, 'items') ? EventResource::collection($this->items()) : [new EventResource($this->resource)],
            'next_cursor'   => method_exists($this->resource, 'nextCursor') ? $this->nextCursor() ? $this->nextCursor()->encode() : null :null,
            'prev_cursor'   => method_exists($this->resource, 'previousCursor') ? $this->previousCursor() ? $this->previousCursor()->encode(): null : null,
        ];
    }

    public function getSimplePaginate():array
    {
        $current_page = $this->currentPage();
        return [
            'data'          => method_exists($this->resource, 'items') ? EventResource::collection($this->items()) : [new EventResource($this->resource)],
            'next_cursor'   => $current_page + 1,
            'prev_cursor'   => method_exists($this->resource, 'nextPage') ? $this->nextPage() ? $this->previousCursor()->encode(): null : null,
        ];
    }

    public function noPageOut():array
    {
        return [
            'data'          => method_exists($this->resource, 'items') ? EventResource::collection($this->items()) : [new EventResource($this->resource)],
            'next_cursor'   => null,
            'prev_cursor'   => null
        ];
    }
}
