<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;

class PlaceStatusesLast implements Pipe {


    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && request()->has('statusLast')){
            $statuses = explode(',', request()->get('statuses'));
            $content->whereHas('eventStatuses', function($q) use ($statuses) {
                $q->whereHas('statuses', function($q) use ($statuses){
                    $q->whereIn("statuses.name", $statuses)->where('last', true);
                });
            });
        }

        return $next($content);
    }
}
