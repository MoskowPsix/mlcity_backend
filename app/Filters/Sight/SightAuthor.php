<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightAuthor implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user')){
            $user = '%'.request()->get('user').'%';

            $content->whereHas('author', function($q) use ($user){
                $q->where('name', 'LIKE', $user)->orWhere('email', 'LIKE', $user);
            });
        }

        return $next($content);
    }
}