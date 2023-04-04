<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventSearchText implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('searchText')){
            $content->where(function($query) {
                $query->orWhere('name', 'LIKE', '%'.request()->get('searchText').'%')
                    ->orWhere('sponsor', 'LIKE', '%'.request()->get('searchText').'%')
                    ->orWhere('description', 'LIKE', '%'.request()->get('searchText').'%');
            });
        }

        return $next($content);
    }
}
