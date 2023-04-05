<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventRegion implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('region') && !request()->has('forEventPage')){
            $content->where('address', 'LIKE', '%'.request()->get('region').'%');
        }

        return $next($content);
    }
}
