<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiMethod implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('method')){
            $content->where(function($query) {
                $query->orWhere('method', 'LIKE', '%'.request()->get('method').'%');
            });
        }

        return $next($content);
    }
}