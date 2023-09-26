<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventPlaceAddress implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('address')){
            $content->whereHas('places', function($query) {
                $query->where('address', 'LIKE', '%'.request()->get('address').'%');
            });
        }

        return $next($content);
    }
}