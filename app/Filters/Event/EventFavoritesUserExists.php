<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Auth;

class EventFavoritesUserExists implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('userId') && request()->has('favoriteUser')){
            $userId = request()->get('userId');

            $content->withExists(['favoritesUsers' => function($q) use ($userId){
                $q->where('user_id', $userId);
            }]);
        }

        return $next($content);
    }
}