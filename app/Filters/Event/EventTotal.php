<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Auth;

class EventTotal implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('total')){

            $content->count();
        }

        return $next($content);
    }
}