<?php

namespace App\Filters\Place;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\DB;

class PlaceGeoPositionInArea implements Pipe {
    //фильтр попадания ивента или места в заданный круг
    public function apply($content, Closure $next)
    {
//        if(request()->filled('latitudeBounds') && request()->filled('longitudeBounds')){
        if(request()->filled('radius') && request()->filled('latitude') && request()->filled('longitude')){
           // $lat_coords = explode(',', request()->get('latitudeBounds'));
            //$lon_coords = explode(',', request()->get('longitudeBounds'));

            $radius = request()->get('radius') * 0.01;

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

            // $upper_latitude = $latitude + (.50); //Change .50 to small values
            // $lower_latitude = $latitude - (.50); //Change .50 to small values
            // $upper_longitude = $longitude + (.50); //Change .50 to small values
            // $lower_longitude = $longitude - (.50); //Change .50 to small values
            info($radius);
            $minLon = $longitude - ($radius);; // минимальная долгота области
            $maxLon = $longitude + ($radius); // максимальная долгота области
            $minLat = $latitude - ($radius); // минимальная широта области
            $maxLat = $latitude + ($radius); // максимальная широта области
            $envelope = DB::raw("ST_MakeEnvelope($minLat, $minLon, $maxLat, $maxLon, 4326)");


            if (request()->has('forEventPage')){
                $content->whereRaw("ST_CONTAINS($envelope, coordinates::geometry)");

                // $content->where('city', '!=' , request()->get('city'))
                //     ->where(function($q) use ($latitude, $longitude, $radius){
                //         $q->whereRaw('(
                //                    6371 *
                //                    acos(cos(radians(?)) *
                //                    cos(radians(latitude)) *
                //                    cos(radians(longitude) -
                //                    radians(?)) +
                //                    sin(radians(?)) *
                //                    sin(radians(latitude )))
                //                 ) <= ? ',
                //         [$latitude, $longitude,  $latitude,  $radius]);
                // });
            } else {
                $content->whereRaw("ST_CONTAINS($envelope, coordinates::geometry)");
//                $content->where(function($q) use ($lat_coords, $lon_coords){
//                    $q->whereBetween('latitude', $lat_coords)
//                        ->whereBetween('longitude', $lon_coords);
//                });
                // $content->where(function($q) use ($latitude, $longitude, $radius){
                //     $q->whereBetween();
                //     $q->whereRaw('(
                //                    6371 *
                //                    acos(cos(radians(?)) *
                //                    cos(radians(latitude)) *
                //                    cos(radians(longitude) -
                //                    radians(?)) +
                //                    sin(radians(?)) *
                //                    sin(radians(latitude )))
                //                 ) <= ? ',
                //     [$latitude, $longitude,  $latitude,  $radius]);
                // });
            }
        }

        return $next($content);
    }
}
