<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationDescription implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('description')){
            $content->where('description','ilike', '%'.request()->get('description').'%');
        }

        return $next($content);
    }
}
