<?php

namespace App\Http\Resources\Sight\Show;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorShowEventResource extends JsonResource
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
            'message'   => __('sight.show.error'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(404);
    }
}
