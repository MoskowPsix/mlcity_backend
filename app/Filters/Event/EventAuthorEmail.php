<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventAuthorEmail implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user_email')){
            $user = '%'.request()->get('user_email').'%';

            $content->whereHas('author', function($q) use ($user){
                $q->where('email', 'LIKE', $user);
            });
        }

        return $next($content);
    }
}