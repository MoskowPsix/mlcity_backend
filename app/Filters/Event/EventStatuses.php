<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventStatuses implements Pipe {

    public function apply($events, Closure $next)
    {
        if(request()->has('statuses') && !request()->has('statusLast')){
            $statuses = explode(',', request()->get('statuses'));

            $events->whereHas('statuses', function($q) use ($statuses){
                $q->whereIn('name', $statuses);
            });
        }

        return $next($events);
    }
}
