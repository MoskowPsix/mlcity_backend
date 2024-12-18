<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Carbon\Carbon;

class EventExpired implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('expired')){
            if(request()->get('expired') === 'true') {
                $now = Carbon::now();
                $content->whereDate('date_end', '<', $now);
            }
            else if (request()->get('expired') === 'false'){
                $now = Carbon::now();
                $content->whereDate('date_end', '>=', $now);
            }
        }
        return $next($content);
    }
}
