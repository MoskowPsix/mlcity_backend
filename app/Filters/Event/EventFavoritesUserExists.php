<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Auth;

class EventFavoritesUserExists implements Pipe {

    public function apply($events, Closure $next)
    {
        if((request()->has('userId') || Auth::user()->id ) && request()->has('favoriteUser')){
            $userId = request()->get('userId') || Auth::user()->id;

            $events->withExists(['favoritesUsers' => function($q) use ($userId){
                $q->where('user_id', $userId);
            }]);
        }

        return $next($events);
    }
}
