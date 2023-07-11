<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersRegion implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('region')){
            $content->where(function($query) {
                $query->orWhere('region', 'LIKE', '%'.request()->get('region').'%');
            });
        }

        return $next($content);
    }
}