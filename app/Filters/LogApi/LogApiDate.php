<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiDate implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('dateStart') && request()->filled('dateEnd')){
            $dateStart = request()->get('dateStart');
            $dateEnd = request()->get('dateEnd');

            $content->where(function($q) use ($dateStart, $dateEnd){
                $q->whereDate('created_at', '<=', $dateEnd)
                ->whereDate('created_at', '>=', $dateStart);
            });
        }
        return $next($content);
    }
}