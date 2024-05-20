<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationInn implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('inn')){
            $content->where('inn',request()->get("inn"));
        }

        return $next($content);
    }
}
