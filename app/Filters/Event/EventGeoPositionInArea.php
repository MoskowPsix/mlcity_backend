<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventGeoPositionInArea implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('latitude') && request()->has('longitude')){
            $lat_coords = explode(',', request()->get('latitude'));
            $lon_coords = explode(',', request()->get('longitude'));

            if (request()->has('forEventPage')){
                $content->where('address', 'LIKE', '%'.request()->get('region').'%')
                    ->where('city', request()->get('city'))
                    ->orWhere(function($q) use ($lat_coords, $lon_coords){
                        $q->whereBetween('latitude', $lat_coords)
                            ->whereBetween('longitude', $lon_coords);
                });
            } else {
                $content->where(function($q) use ($lat_coords, $lon_coords){
                    $q->whereBetween('latitude', $lat_coords)
                        ->whereBetween('longitude', $lon_coords);
                });
            }
        }

        return $next($content);
    }
}
