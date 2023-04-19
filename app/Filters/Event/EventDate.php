<?php

namespace App\Filters\Event;

use Closure;
use Carbon\Carbon;
use App\Filters\Pipe;

class EventDate implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('dateStart') && request()->filled('dateEnd')){
            $dateStart = request()->get('dateStart');
            $dateEnd = request()->get('dateEnd');

            $content->where(function($q) use ($dateStart, $dateEnd){
                $q->whereDate('date_start', '<=', $dateEnd)
                ->whereDate('date_end', '>=', $dateStart);
            });
        }
        return $next($content);
    }
}
