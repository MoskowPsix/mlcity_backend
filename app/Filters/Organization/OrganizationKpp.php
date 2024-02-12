<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationKpp implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('kpp')){
            $content->where('kpp',request()->get("kpp"));
        }

        return $next($content);
    }
}
