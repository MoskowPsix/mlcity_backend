<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightOrderBy implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->filled('orderBy')){
            if(request()->get("desc") == True){
                $content->orderBy(request()->get('orderBy'), 'desc');
            }
            else{
                $content->orderBy(request()->get('orderBy'));
            }
        }

        return $next($content);
    }
}
