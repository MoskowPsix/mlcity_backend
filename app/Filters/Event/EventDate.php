<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventDate implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('dateStart') && request()->filled('dateEnd')){
            $dateStart = request()->get('dateStart');
            $dateEnd = request()->get('dateEnd');

            $content->whereHas("places.seances", function($q) use ($dateStart, $dateEnd){
                $q->whereDate('date_start', '<=', $dateEnd)
                ->whereDate('date_start', '>=', $dateStart);
            });
        }
        return $next($content);
    }
}
