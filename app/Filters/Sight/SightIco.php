<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightIco implements Pipe {

    public function apply($content, Closure $next)
    {
        $content->with("types");

        return $next($content);
    }
}
