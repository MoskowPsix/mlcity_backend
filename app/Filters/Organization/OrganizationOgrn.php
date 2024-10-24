<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationOgrn implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('ogrn')){
            $content->where('ogrn',request()->get("ogrn"));
        }

        return $next($content);
    }
}
