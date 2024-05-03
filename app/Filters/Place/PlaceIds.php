<?php

namespace App\Filters\Place;


use Closure;
use App\Filters\Pipe;

class PlaceIds implements Pipe{
    public function apply($content, Closure $next){
        if (request()->has("placeIds")){
            $placesIds = $this->parseIds(request()->get("placeIds"));
            info($placesIds);
            $content->whereIn("id", $placesIds);
        }
        return $next($content);
    }

    public function parseIds($array) {
        $array = trim($array, "[");
        $array = trim($array, "]");
        $placeIds = explode(",", $array);
        $placeIds = array_map('intval', $placeIds);

        return $placeIds;
    }
}
