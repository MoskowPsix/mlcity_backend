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
        if (request()->filled('createdDateStart')) {
            $dateStart = request()->get('createdDateStart');
            $content->where(function($q) use ($dateStart){
                $q->whereDate('created_at', '>=', $dateStart);
            });
        } elseif (request()->filled('cteatedDateEnd')) {
            $dateEnd = request()->get('cteatedDateEnd');
            $content->where(function($q) use ($dateEnd){
                $q->whereDate('created_at', '<=', $dateEnd);
            });
        }
        return $next($content);
    }
}