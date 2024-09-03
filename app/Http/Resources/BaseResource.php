<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{

    public static function collection($resource): CustomResourceCollection
    {
        $res = tap(new CustomResourceCollection($resource, static::class), function ($collection) {
            info($collection->preserveKeys);
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });;
        return $res;
    }
}
