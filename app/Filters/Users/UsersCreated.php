<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersCreated implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('createdDateStart') && request()->filled('cteatedDateEnd')){
            $dateStart = request()->get('createdDateStart');
            $dateEnd = request()->get('cteatedDateEnd');

            $content->where(function($q) use ($dateStart, $dateEnd){
                $q->whereDate('created_at', '<=', $dateEnd)
                ->whereDate('created_at', '>=', $dateStart);
            });
        }
        return $next($content);
    }
}