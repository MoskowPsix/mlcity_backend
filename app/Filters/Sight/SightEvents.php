<?php

namespace App\Filters\Sight;
use Closure;
use App\Filters\Pipe;

class SightEvents implements Pipe{
    public function apply($content, Closure $next)
    {
        if(request()->get('events') == true){
            $content->with("events");
        }

        return $next($content);
    }

}
