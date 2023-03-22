<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Auth;

class EventLikedUserExists implements Pipe {

    public function apply($events, Closure $next)
    {
        if((request()->has('userId') || Auth::user()->id ) && request()->has('likedUser')){
            $userId = request()->get('userId') || Auth::user()->id;

            $events->withExists(['likedUsers' => function($q) use ($userId){
                $q->where('user_id', $userId);
            }]);
        }

        return $next($events);
    }
}
