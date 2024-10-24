<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersEmail implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('email')){
            $content->where(function($query) {
                $query->orWhere('email', 'LIKE', '%'.request()->get('email').'%');
            });
        }

        return $next($content);
    }
}
