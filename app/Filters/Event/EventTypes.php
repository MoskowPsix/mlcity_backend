<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventTypes implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->filled('eventTypes')){
            $types= explode(',', request()->get('eventTypes'));

            $content->whereHas('types', function($q) use ($types) {
                $q->whereIn('etypes.etype_id', $types);
            });
        }

        return $next($content);
    }
}
