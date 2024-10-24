<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceWithEvent implements Pipe{
    public function apply($content, Closure $next)
    {
        if(request()->has("events") && request()->get("events") == true){
            $content->with("event");
        }

        return $next($content);
    }
}
