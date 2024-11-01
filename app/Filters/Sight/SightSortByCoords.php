<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightSortByCoords implements Pipe
{
    public function apply($content, Closure $next)
    {
        if(request()->has('latitude_position') && request()->has('longitude_position')){
            $content
                ->orderByRaw('ABS(latitude - ?)', request()->get('latitude_position'))
                ->orderByRaw('ABS(longitude - ?)', request()->get('longitude_position'));
        }

        return $next($content);
    }
}
