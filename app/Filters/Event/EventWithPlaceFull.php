<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

 class EventWithPlaceFull implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->get("withPlacesFull") == true){
            $content->with("placesFull");
        }

        return $next($content);
    }
}
