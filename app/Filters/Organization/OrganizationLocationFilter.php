<?php

namespace App\Filters\Organization;

use Closure;
use App\Filters\Pipe;

class OrganizationLocationFilter implements Pipe
{
    public function apply($content, Closure $next)
    {
        if(request()->has('locationId')){
            $location_id = request()->get('locationId');
            $content->whereHas('locations', function($q) use ($location_id){
                $q->where('locations.id', $location_id);
            });
        }

        return $next($content);
    }
}
