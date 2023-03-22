<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventGeoPositionInArea implements Pipe {

    public function apply($events, Closure $next)
    {
        if(request()->has('latitude') && request()->has('longitude')){
            $lat_coords = explode(',', request()->get('latitude'));
            $lon_coords = explode(',', request()->get('longitude'));

            $events->where(function($q) use ($lat_coords, $lon_coords){
                $q->whereBetween('latitude', $lat_coords)
                    ->whereBetween('longitude', $lon_coords);
            });
        }

        return $next($events);
    }
}
