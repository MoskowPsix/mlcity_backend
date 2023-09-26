<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventPlaceLocation implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){
            $content->whereHas('places', function($query) {
                $query->where('location_id', request()->get('locationId'));
            });
        }

        return $next($content);
    }
}