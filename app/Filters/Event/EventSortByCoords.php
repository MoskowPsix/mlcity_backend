<?php

namespace App\Filters\Event;

use App\Models\Place;
use Closure;
use App\Filters\Pipe;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Facades\DB;

class EventSortByCoords implements Pipe
{
    public function apply($content, Closure $next)
    {
        // Проверяем наличие координат в запросе
        if (request()->has('latitude_position') && request()->has('longitude_position') && !request()->has('eventIds')) {
            $latitude = request()->get('latitude_position');
            $longitude = request()->get('longitude_position');
//            $content->select([
//                'events.*',
//                DB::raw("
//                    6371 * acos(
//                        cos(radians($latitude)) * cos(radians(places.latitude)) *
//                        cos(radians(places.longitude) - radians($longitude)) +
//                        sin(radians($latitude)) * sin(radians(places.latitude))
//                    ) as distance,
//                    locations.name as location
//                "),
//                DB::raw("
//                    (
//                        SELECT MIN(6371 * acos(
//                            cos(radians($latitude)) * cos(radians(places.latitude)) *
//                            cos(radians(places.longitude) - radians($longitude)) +
//                            sin(radians($latitude)) * sin(radians(places.latitude))
//                        ))
//                        FROM places
//                        WHERE places.event_id = events.id
//                    ) as distance
//                "),
//                DB::raw("
//                    (
//                        SELECT locations.name
//                        FROM places
//                        JOIN locations ON locations.id = places.location_id
//                        WHERE places.event_id = events.id
//                        ORDER BY
//                            6371 * acos(
//                                cos(radians($latitude)) * cos(radians(places.latitude)) *
//                                cos(radians(places.longitude) - radians($longitude)) +
//                                sin(radians($latitude)) * sin(radians(places.latitude))
//                            )
//                        LIMIT 1
//                    ) as location_name
//                ")
//            ])
//                ->orderBy('distance')->distinct();
//        }
            $ids = $this->getIds($latitude, $longitude);
            $content->whereIn('id', $ids)->distinct();
            return $next($content);

        }
    }

    private function getIds($latitude, $longitude): array {
        $model = new Place();
        $searchParams = [
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            '_source' => ['id', 'event_id', 'location'], // необходимые поля
            'size' => 100, // общее количество записей (можно использовать scroll при необходимости)
            'body' => [
                "sort" => [
                    '_geo_distance' => [
                        'location' => [
                            'lat' => $latitude,
                            'lon' => $longitude
                        ],
                        'order' => 'asc',
                        'unit' => 'km',
                        'distance_type' => 'arc',
                    ]
                ],
            ],
        ];
        $items = resolve(Client::class)->search($searchParams)->asArray();
        foreach ($items['hits']['hits'] as $item) {
            $ids[] = $item['_source']['event_id'];
        }
        return $ids;
    }
}
