<?php

namespace App\Filters\HistoryContent;

use Closure;
use App\Filters\Pipe;

class HistoryContentEventTypes implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->filled('types')){
            $types= explode(',', request()->get('types'));

            $content->whereHas('eventTypes', function($q) use ($types){
                $q->whereIn('history_contenttables.history_contetable_id', $types);
            });
        }

        return $next($content);
    }
}