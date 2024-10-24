<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersLocation implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){
            $content->where(function($query) {
                $query->orWhere('location_id', request()->get('locationId'));
            });
        }

        return $next($content);
    }
}