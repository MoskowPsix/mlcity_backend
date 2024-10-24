<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventSponsor implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('sponsor')){
            $content->where(function($query) {
                $query->orWhere('sponsor', 'ilike', '%'.request()->get('sponsor').'%');
            });
        }

        return $next($content);
    }
}