<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

class OrganizationLocationFilter
{
    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){
            $location_id = request()->has('locationId');
            $content-whereHas('location', function($q) use($location_id){
                $q->where('location_id', $location_id);
            });
        }

        return $next($content);
    }
}
