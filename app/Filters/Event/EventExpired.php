<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventDate implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('expired') && request()->get('expired')){
            $content->where('date_end', '<', )
        }
        return $next($content);
    }
}
