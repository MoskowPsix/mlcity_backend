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

            $statusNames = explode(',', $statuses);
            if (is_numeric($statusNames[0])) {
                $status = Status::find($statusNames[0]);
            } else {
                $status = Status::where('name', $statusNames[0])->first();
            }

            if ($status) {
                $content->whereHas('statuses', function($q) use ($status){
                    $q->where('status_id', $status->id);
                });
            }
        }

        return $next($content);
    }
}
