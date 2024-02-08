<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;

class PlaceStatuses implements Pipe {

    public function apply($content, Closure $next)
    {
        $statuses = explode(',', request()->get('statuses'));

        $content->whereHas('eventStatuses', function($q) use ($statuses) {
            $q->whereHas('statuses', function($q) use ($statuses){
                $q->whereIn("statuses.name", $statuses);
            });
        });

        return $next($content);
    }
}
