<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceStatuses implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && !request()->has('statusLast')){
            $statuses = '%'.request()->get('statuses').'%';

            $content->whereHas('eventStatuses', function($query) use ($statuses) {
                $query->whereHas('statuses', function($q) use ($statuses){
                    foreach($q as $query) {
                        $query->where('name', 'LIKE', $statuses);
                    }
                });
            });
        }

        return $next($content);
    }
}