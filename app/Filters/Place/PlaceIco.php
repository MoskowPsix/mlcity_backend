<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceIco implements Pipe {

    public function apply($content, Closure $next)
    {
        $content->with("event.types");

        return $next($content);
    }
}
