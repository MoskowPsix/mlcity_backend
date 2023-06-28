<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventAuthorName implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user_name')){
            $user = '%'.request()->get('user_name').'%';

            $content->whereHas('author', function($q) use ($user){
                $q->where('name', 'LIKE', $user);
            });
        }

        return $next($content);
    }
}