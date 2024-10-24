<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationUser implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('user') && request()->get("user") == true){
            $content->with("user");
        }

        return $next($content);
    }
}
