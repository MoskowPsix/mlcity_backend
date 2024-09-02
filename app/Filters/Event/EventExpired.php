<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Carbon\Carbon;

class EventExpired implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('expired') && request()->get('expired')){
            $content->where('date_end', '<', Carbon::now());
        }
        return $next($content);
    }
}
