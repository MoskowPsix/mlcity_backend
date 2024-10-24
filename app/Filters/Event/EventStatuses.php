<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;

class EventStatuses implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && !request()->has('statusLast')){
            $statuses = request()->get('statuses');
            if ($statuses == "Все") {
                return $next($content);
            }
            $status = Status::where('name', $statuses)->first()->id;
            $content->whereHas('statuses', function($q) use ($status){
                $q->where('status_id', $status);
            });
        }

        return $next($content);
    }
}
