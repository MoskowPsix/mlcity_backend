<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventStatusesLast implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && request()->has('statusLast')){
            $statuses = explode(',', request()->get('statuses'));

            $content->whereHas('statuses', function($q) use ($statuses){
                $q->whereIn('name', $statuses)->where('last', true);
            });
        }

        return $next($content);
    }
}
