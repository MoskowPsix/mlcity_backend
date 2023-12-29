<?php

namespace App\Filters\Type;

use Closure;
use App\Filters\Pipe;

 class TypeName implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('name')){
            $content->where(function($query) {
                $query->orWhere('name', 'LIKE', '%'.request()->get('name').'%');
            });
        }

        return $next($content);
    }
}