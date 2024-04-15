<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationAddress implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('address')){
            $content->where('address','LIKE', '%'.request()->get('address').'%');
        }

        return $next($content);
    }
}
