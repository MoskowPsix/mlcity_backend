<?php

namespace App\Filters\Location;

use Closure;
use App\Filters\Pipe;


class LocationDisplay implements Pipe {
    public function apply($content, Closure $next)
    {
        if(request()->get("display") == true) {
            $content->where("display", true);
        }

        return $next($content);
    }
}
