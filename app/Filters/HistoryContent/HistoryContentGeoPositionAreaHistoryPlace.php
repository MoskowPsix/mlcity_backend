<?php

namespace App\Filters\HistoryContent;

use Closure;
use App\Filters\Pipe;

class HistoryContentGeoPositionAreaHistoryPlace implements Pipe {
    //фильтр попадания ивента или места в заданный круг
    public function apply($content, Closure $next)
    {
        if(request()->filled('radius') && request()->filled('latitude') && request()->filled('longitude')){
            $radius = request()->get('radius');
            $latitude =  request()->get('latitude');
            $longitude  =  request()->get('longitude');

            $content->whereHas('historyPlaces', function($q) use ($latitude, $longitude, $radius){
                $q->whereRaw('(
                                6371 *
                                acos(cos(radians(?)) *
                                cos(radians(latitude)) *
                                cos(radians(longitude) -
                                radians(?)) +
                                sin(radians(?)) *
                                sin(radians(latitude )))
                            ) <= ? ',
                [$latitude, $longitude,  $latitude,  $radius]);
            });
        }

        return $next($content);
    }
}