<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventCity implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('city') && !request()->has('forEventPage')){
            $content->where('city', request()->get('city'));
        }

        return $next($content);
    }
}
