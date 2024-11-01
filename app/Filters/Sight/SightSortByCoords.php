<?php

namespace App\Filters\Sight;

use Closure;
use App\Filters\Pipe;

class SightSortByCoords implements Pipe
{
    public function apply($content, Closure $next)
    {
        if(request()->has('latitude_position') && request()->has('longitude_position') && !request()->has('sightIds')){
            $latitude = request()->get('latitude_position');
            $longitude = request()->get('longitude_position');

            $content
                ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$latitude, $longitude, $latitude])
                ->orderBy('distance');
        }

        return $next($content);
    }
}
