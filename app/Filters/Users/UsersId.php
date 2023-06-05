<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersId implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('id')){
            $content->where('id', request()->get('id'));
        }

        return $next($content);
    }
}