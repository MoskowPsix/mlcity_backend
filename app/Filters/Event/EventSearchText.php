<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventSearchText implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('searchText')){
            $content->where(function($query) {
                $query->orWhere('name', 'ilike', '%'.request()->get('searchText').'%')
                    ->orWhere('sponsor', 'ilike', '%'.request()->get('searchText').'%')
                    ->orWhere('description', 'ilike', '%'.request()->get('searchText').'%');
            });
        }

        return $next($content);
    }
}
