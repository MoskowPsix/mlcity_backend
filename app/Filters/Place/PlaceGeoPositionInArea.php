<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;

class PlaceGeoPositionInArea implements Pipe {
    //фильтр попадания ивента или места в заданный круг
    public function apply($content, Closure $next)
    {
//        if(request()->filled('latitudeBounds') && request()->filled('longitudeBounds')){
        if(request()->filled('radius') && request()->filled('latitude') && request()->filled('longitude')){
           // $lat_coords = explode(',', request()->get('latitudeBounds'));
            //$lon_coords = explode(',', request()->get('longitudeBounds'));

            $radius = request()->get('radius');

            $latitude =  request()->get('latitude');
            $longitude  =  request()->get('longitude');

//            if (request()->has('forEventPage')){
//                $content->where('address', 'LIKE', '%'.request()->get('region').'%')
//                    ->where('city', request()->get('city'))
//                    ->orWhere(function($q) use ($lat_coords, $lon_coords){
//                        $q->whereBetween('latitude', $lat_coords)
//                            ->whereBetween('longitude', $lon_coords);
//                    });
//            }

            if (request()->has('forEventPage')){
                $content->where('city', '!=' , request()->get('city'))
                    ->where(function($q) use ($latitude, $longitude, $radius){
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
            } else {
//                $content->where(function($q) use ($lat_coords, $lon_coords){
//                    $q->whereBetween('latitude', $lat_coords)
//                        ->whereBetween('longitude', $lon_coords);
//                });
                $content->where(function($q) use ($latitude, $longitude, $radius){
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
        }

        return $next($content);
    }
}
