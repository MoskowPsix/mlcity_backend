<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventCity implements Pipe {

    public function apply($events, Closure $next)
    {
        if(request()->has('city')){
            $events->where('city', request()->get('city'));
        }

        return $next($events);
    }
}
