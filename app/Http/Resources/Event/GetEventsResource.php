<?php
//
//namespace App\Http\Resources\Event;
//
//use App\Http\Controllers\Api\EventController;
//use Illuminate\Http\Request;
//use Illuminate\Http\Resources\Json\JsonResource;
//
///**
// * @property object $events
// */
//class GetEventsResource extends JsonResource
//{
//    /**
//     * Transform the resource into an array.
//     *
//     * @param  Request  $request
//     * @return array
//     */
//    public function toArray($request): array
//    {
//        return [
//            'status' => 'success',
//            'events' => $this['events']['data']
//        ];
//    }
//}


namespace App\Http\Resources\Event;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GetEventsResource extends JsonResource
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
            'events' => EventResource::collection($this->resource),
        ];
    }
}

