<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;


class EventStatusesLast implements Pipe {

    public function apply($content, Closure $next)
    {
        if(true){
            $statuses = explode(',', request()->get('statuses'));
            $status_id = Status::where('name', $statuses[0])->first()->id;
            $content->whereHas('statuses', function($q) use ($status_id){
                $q->where('last', true)->where('status_id', $status_id);
            });
        }

        return $next($content);
    }
}
