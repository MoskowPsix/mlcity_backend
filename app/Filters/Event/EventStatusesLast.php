<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;


class EventStatusesLast implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && request()->has('statusLast')){
            $statuses = explode(',', request()->get('statuses'));
            // $status = Status::where('name', $statuses[0])->first()->id;

            $content->whereHas('statuses', function($q) use ($statuses){
                $q->whereIn('status_id', $statuses)->where('last', true);
            });
        }

        return $next($content);
    }
}
