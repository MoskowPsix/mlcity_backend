<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventFiles implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('withFiles') && request()->get('files')){
            $content->with('files');
        }
        return $next($content);
    }
}
