<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightLocation implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){
            if(is_numeric(request()->get('locationId'))) {
                $content->where(function($query) {
                    $query->orWhere('location_id', request()->get('locationId'));
                });
            } else if (!is_numeric(request()->get('locationId'))) {
                $content->where(function($query) {
                    $query->whereHas('locations', function($query) {
                        $query->where('name', 'LIKE' ,request()->get('locationId'));
                    });
                });
            }

        }

        return $next($content);
    }
}