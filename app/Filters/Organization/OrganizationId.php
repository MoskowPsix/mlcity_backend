<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

 class OrganizationId implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('organization_id')){
            $content->where('id',request()->get("organization_id"));
        }

        return $next($content);
    }
}
