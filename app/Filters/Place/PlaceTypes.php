<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceTypes implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->filled('eventTypes')){
            $types= explode(',', request()->get('eventTypes'));

            $content->whereHas('eventTypes', function($q) use ($types) {
                    $q->whereHas('types', function($q) use ($types) {
                        $q->whereIn('events_etypes.etype_id', $types);
                    });
            });
        }

        return $next($content);
    }
}
