<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;
use App\Models\Status;

class PlaceStatusesLast implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && request()->has('statusLast')){
            $statuses_name = explode(',', request()->get('statuses'));
            $statuses_id = [];
            foreach($statuses_name as $status_name) {
                $status = Status::where('name', $status_name)->first();
                if($status) {
                    $statuses_id[] = $status->id;
                }
            }
            $content->whereHas('eventStatuses', function($q) use ($statuses_id) {
                $q->whereHas('statuses', function($q) use ($statuses_id){
                    $q->whereIn("event_status.status_id", $statuses_id)->where('last', true);
                });
            });
        }

        return $next($content);
    }
}
