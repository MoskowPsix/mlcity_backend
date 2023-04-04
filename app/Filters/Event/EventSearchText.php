<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventSearchText implements Pipe {

    public function apply($events, Closure $next)
    {
        if(request()->has('searchText')){
            $events->orWhere('name', 'LIKE', '%'.request()->get('searchText').'%')
                ->orWhere('sponsor', 'LIKE', '%'.request()->get('searchText').'%')
                ->orWhere('description', 'LIKE', '%'.request()->get('searchText').'%')
                ->get();
        }

        return $next($events);
    }
}
