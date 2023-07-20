<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiIp implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('ip')){
            $content->where(function($query) {
                $query->orWhere('ip', 'LIKE', '%'.request()->get('ip').'%');
            });
        }

        return $next($content);
    }
}