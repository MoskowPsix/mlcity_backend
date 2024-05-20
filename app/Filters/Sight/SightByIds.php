<?php

namespace App\Filters\Sight;

use App\Filters\Pipe;
use Closure;

class SightByIds implements Pipe {
    public function apply($content, Closure $next)
    {
        if(request()->has("sightIds")){
            $sightIds = explode(",",request()->get("sightIds"));
            $content->whereIn("id", $sightIds);
        }
        return $next($content);
    }
}
