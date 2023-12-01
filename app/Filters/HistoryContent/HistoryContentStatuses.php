<?php

namespace App\Filters\HistoryContent;

use Closure;
use App\Filters\Pipe;

class HistoryContentStatuses implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('statuses') && !request()->has('statusLast')){
            $statuses = '%'.request()->get('statuses').'%';

            $content->whereHas('statuses', function($q) use ($statuses){
                $q->where('name', 'LIKE', $statuses);
            });
        }

        return $next($content);
    }
}