<?php

namespace App\Filters\HistoryContent;

use Closure;
use App\Filters\Pipe;

class HistoryContentAuthor implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user')){
            $user = '%'.request()->get('user').'%';

            $content->whereHas('user', function($q) use ($user){
                $q->where('name', 'LIKE', $user)->orWhere('email', 'LIKE', $user);
            });
        }

        return $next($content);
    }
}