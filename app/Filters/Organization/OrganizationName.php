<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationName implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('name')){
            $content->where('name', 'ilike', '%'.request()->get('name').'%');
        }

        return $next($content);
    }
}
