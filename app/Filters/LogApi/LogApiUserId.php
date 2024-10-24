<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiUserId implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user_id')){
            $content->where('user_id', request()->get('user_id'));
        }

        return $next($content);
    }
}