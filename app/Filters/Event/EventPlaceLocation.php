<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Log;

class EventPlaceLocation implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){

            if(is_numeric(request()->get('locationId'))) {
                $content->whereHas('places', function($query) {
                    $query->where('location_id', request()->get('locationId'));
                });
            } else if (!is_numeric(request()->get('locationId'))) {
                $content->whereHas('places', function($query) {
                    $query->whereHas('location', function($query) {
                        $query->where('name', 'LIKE' ,request()->get('locationId'));
                    });
                });
            }

        }

        return $next($content);
    }
}