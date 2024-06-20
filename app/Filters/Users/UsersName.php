<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersName implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('name')){
            $content->where(function($query) {
                $query->orWhere('name', 'ilike', '%'.request()->get('name').'%');
            });
        }

        return $next($content);
    }
}