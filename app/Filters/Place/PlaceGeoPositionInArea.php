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

            // $upper_latitude = $latitude + (.50); //Change .50 to small values
            // $lower_latitude = $latitude - (.50); //Change .50 to small values
            // $upper_longitude = $longitude + (.50); //Change .50 to small values
            // $lower_longitude = $longitude - (.50); //Change .50 to small values

            $minLon = $longitude - (.50);; // минимальная долгота области
            $maxLon = $longitude + (.50); // максимальная долгота области
            $minLat = $latitude - (.50); // минимальная широта области
            $maxLat = $latitude + (.50); // максимальная широта области


            if (request()->has('forEventPage')){
                $envelope = DB::raw("ST_MakeEnvelope($minLat, $minLon, $maxLat, $maxLon, 4326)");
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

                $envelope = DB::raw("ST_MakeEnvelope($minLat, $minLon, $maxLat, $maxLon, 4326)");
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
