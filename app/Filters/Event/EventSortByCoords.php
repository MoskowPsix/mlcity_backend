<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\DB;

class EventSortByCoords implements Pipe
{
    public function apply($content, Closure $next)
    {
        // Проверяем наличие координат в запросе (position для сортировки, либо map center)
        $latitude = request()->get('latitude_position', request()->get('latitude'));
        $longitude = request()->get('longitude_position', request()->get('longitude'));

        if ($latitude !== null && $longitude !== null && $latitude !== '' && $longitude !== '' && !request()->has('eventIds')) {
            $latitude = (float) $latitude;
            $longitude = (float) $longitude;
            $content->select([
                'events.*',
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
                "),
                DB::raw("
                    (
                        SELECT locations.name
                        FROM places
                        JOIN locations ON locations.id = places.location_id
                        WHERE places.event_id = events.id
                        ORDER BY
                            6371 * acos(
                                cos(radians($latitude)) * cos(radians(places.latitude)) *
                                cos(radians(places.longitude) - radians($longitude)) +
                                sin(radians($latitude)) * sin(radians(places.latitude))
                            )
                        LIMIT 1
                    ) as location_name
                ")
            ])
                ->orderBy('distance')->distinct();
        }
        return $next($content);

    }
}
