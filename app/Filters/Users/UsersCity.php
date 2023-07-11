<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersCity implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('city')){
            $content->where(function($query) {
                $query->orWhere('city', 'LIKE', '%'.request()->get('city').'%');
            });
        }

        return $next($content);
    }
}