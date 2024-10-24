<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiUrl implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('url')){
            $content->where(function($query) {
                $query->orWhere('url', 'LIKE', '%'.request()->get('url').'%');
            });
        }

        return $next($content);
    }
}