<?php

namespace App\Filters\LogApi;

use Closure;
use App\Filters\Pipe;

class LogApiRequest implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('request')){
            $content->where(function($query) {
                $query->orWhere('requst_arg', 'LIKE', '%'.request()->get('request').'%')
                      ->orWhere('requst_header', 'LIKE', '%'.request()->get('request').'%');
            });
        }

        return $next($content);
    }
}