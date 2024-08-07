<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceDate implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('dateStart') && request()->filled('dateEnd')){
            $dateStart = request()->get('dateStart');
            $dateEnd = request()->get('dateEnd');

            // $content->where(function($q) use ($dateStart, $dateEnd){
            //     $q->whereDate('date_start', '<=', $dateEnd)
            //     ->whereDate('date_end', '>=', $dateStart);
            // });

            $content->whereHas('seances', function($query) use ($dateStart, $dateEnd) {
                $query->whereDate('date_start', '<=', $dateEnd)
                    ->whereDate('date_end', '>=', $dateStart);
            });
        }
        return $next($content);
    }
}
