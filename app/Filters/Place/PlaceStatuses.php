<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;

class PlaceStatuses implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && !request()->has('statusLast')) {
            $statuses = explode(',', request()->get('statuses'));
            // $status = Status::where('name', $statuses[0])->first()->id;
            $content->whereHas('eventStatuses', function($q) use ($statuses) {
                $q->whereHas('statuses', function($q) use ($statuses){
                    $q->where("name", $statuses);
                });
            });
        }

        return $next($content);
    }
}
