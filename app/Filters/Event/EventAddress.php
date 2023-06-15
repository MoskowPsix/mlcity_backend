<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventAddress implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('address')){
            $content->where(function($query) {
                $query->orWhere('address', 'LIKE', '%'.request()->get('address').'%');
            });
        }

        return $next($content);
    }
}