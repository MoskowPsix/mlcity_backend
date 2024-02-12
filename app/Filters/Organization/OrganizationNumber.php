<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationNumber implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('number')){
            $content->where('number','LIKE', '%'.request()->get('number').'%');
        }

        return $next($content);
    }
}
