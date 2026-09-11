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
            if ($statuses[0] == "Все") {
                return $next($content);
            }

            // Same as EventStatusesLast: client may send id or name
            if (is_numeric($statuses[0])) {
                $status = Status::find($statuses[0]);
            } else {
                $status = Status::where('name', $statuses[0])->first();
            }

            if ($status) {
                $content->whereHas('eventStatuses', function($q) use ($status) {
                    $q->whereHas('statuses', function($q) use ($status) {
                        $q->where('status_id', $status->id)->where('last', true);
                    });
                });
            }
        }

        return $next($content);
    }
}
