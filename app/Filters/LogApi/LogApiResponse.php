<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiResponse implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('response')){
            $content->where(function($query) {
                $query->orWhere('response', 'LIKE', '%'.request()->get('response').'%');
            });
        }

        return $next($content);
    }
}