<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\DB;

class EventSortByCoords implements Pipe
{
    public function apply($content, Closure $next)
    {
        // Проверяем наличие координат в запросе
        if (request()->has('latitude_position') && request()->has('longitude_position') && !request()->has('eventIds')) {
            $latitude = request()->get('latitude_position');
            $longitude = request()->get('longitude_position');
            $content->select([
                'events.*',
                'locations.name as location_name',
                DB::raw("
                    (
                        SELECT MIN(6371 * acos(
                            cos(radians($latitude)) * cos(radians(places.latitude)) *
                            cos(radians(places.longitude) - radians($longitude)) +
                            sin(radians($latitude)) * sin(radians(places.latitude))
                        ))
                        FROM places
                        WHERE places.event_id = events.id
                    ) as distance
                ")
            ])
                ->join('places', 'events.id', '=', 'places.event_id')
                ->join('locations', 'places.location_id', '=', 'locations.id')
                ->orderBy('distance')->distinct()->take(1);

        }
        return $next($content);

    }
}
