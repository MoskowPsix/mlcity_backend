<?php

namespace App\Filters\Users;

use Closure;
use App\Filters\Pipe;

class UsersUpdated implements Pipe {

    public function apply($content, Closure $next)
    {
        if (request()->filled('updatedDateStart') && request()->filled('updatedDateEnd')){
            $dateStart = request()->get('updatedDateStart');
            $dateEnd = request()->get('updatedDateEnd');

            $content->where(function($q) use ($dateStart, $dateEnd){
                $q->whereDate('updated_at', '<=', $dateEnd)
                ->whereDate('updated_at', '>=', $dateStart);
            });
        }

        if (request()->filled('updatedDateStart')) {
            $dateStart = request()->get('updatedDateStart');
            $content->where(function($q) use ($dateStart){
                $q->whereDate('updated_at', '>=', $dateStart);
            });
        } elseif (request()->filled('updatedDateEnd')) {
            $dateEnd = request()->get('updatedDateEnd');
            $content->where(function($q) use ($dateEnd){
                $q->whereDate('updated_at', '<=', $dateEnd);
            });
        }
        return $next($content);
    }
}