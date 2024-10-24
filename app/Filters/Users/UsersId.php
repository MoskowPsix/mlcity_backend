<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersId implements Pipe {

    public function apply($content, Closure $next)
    {
        if(!empty(request()->get('id'))){
            if(request()->has('id')){
                $content->where(function($query) {
                    $query->orWhere('id', request()->get('id'));
                });
            }
        } else {
            $content->orWhere('id', 'LIKE' , '%%');
        }
        return $next($content);  
    }
}