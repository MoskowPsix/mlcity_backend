<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiStatusCode implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('status_code')){
            $content->where(function($query) {
                $query->orWhere('status_code', 'LIKE', '%'.request()->get('status_code').'%');
            });
        }

        return $next($content);
    }
}