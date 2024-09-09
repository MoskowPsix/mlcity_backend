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
                info(request()->get('expired'));

                $content->where('date_end', '<', Carbon::now());
            } else if (request()->get('expired') === 'false'){
                info("ITS IT");
                $now = Carbon::now();
                $content->where('date_start', '<=', $now)->where('date_end', '>=', $now);
            }
        }
        return $next($content);
    }
}
