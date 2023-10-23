<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightTypes implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->filled('sightTypes')){
            $types= explode(',', request()->get('sightTypes'));

            $content->whereHas('types', function($q) use ($types){
                $q->whereIn('sights_stypes.stype_id', $types);
            });
        }

        return $next($content);
    }
}
