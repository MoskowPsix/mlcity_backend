<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

 class EventName implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('name')){
            $content->where(function($query) {
                $query->orWhere('name', 'ilike', '%'.request()->get('name').'%');
            });
        }

        return $next($content);
    }
}