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
            // Клиент присылает id статуса
            if((is_numeric($statuses[0]))) {
                $status_id = Status::find($statuses[0])->id;
            }
            // Админ присылает название статуса 
            else {
                $status_id = Status::where('name', $statuses[0])->first()->id;
            }
            $content->whereHas('statuses', function($q) use ($status_id){
                $q->where('last', true)->where('status_id', $status_id);
            });
        }

        return $next($content);
    }
}
