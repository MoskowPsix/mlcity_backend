<?php

namespace App\Filters\Event;

use App\Filters\Pipe;
use Closure;

class EventByIds implements Pipe {
    public function apply($content, Closure $next)
    {
        if(request()->has("eventIds")){
            $eventIds = explode(",",request()->get("eventIds"));
            $content->whereIn("id", $eventIds);
        }
        return $next($content);
    }
}
