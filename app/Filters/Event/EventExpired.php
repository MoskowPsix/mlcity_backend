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
                $content->whereDoesntHas("places.seances", function($q) use ($now){
                    $q->whereDate('date_start', '=>', $now);
                });
            } else if (request()->get('expired') === 'false'){
                $now = Carbon::now();
                $content->whereHas("places.seances", function($q) use ($now){
                    $q->whereDate('date_start', '<=', $now)
                    ->whereDate('date_end', '>=', $now);
                });
            }
        }
        return $next($content);
    }
}
