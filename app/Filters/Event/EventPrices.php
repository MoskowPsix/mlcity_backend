<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventPrices implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('withPrices') && request()->get('withPrices')){
            $content->with('price');
        }
        return $next($content);
    }
}
