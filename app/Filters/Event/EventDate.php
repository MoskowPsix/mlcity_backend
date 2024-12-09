<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\DB;

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
//            $content->whereHas('places', function ($query) use ($dateStart, $dateEnd) {
//                $query->whereHas('seances', function ($query) use ($dateStart, $dateEnd) {
//                    $query->whereBetween(DB::raw('DATE(date_start)'), [$dateStart, $dateEnd]);
//                });
//            });
        }
        return $next($content);
    }
}
