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
            // Добавляем подзапрос для расчета расстояния
//            $query = $content
//                ->join('places', 'events.id', '=', 'places.event_id')
//                ->selectRaw("(6371 * acos(cos(radians(?)) * cos(radians(places.latitude)) * cos(radians(places.longitude) - radians(?)) + sin(radians(?)) * sin(radians(places.latitude)))) AS distance", [$latitude, $longitude, $latitude])
//                ->groupBy('events.id')
//                ->orderBy('distance');
//            $content->with(['places' => function($query) use($latitude, $longitude) {
//                $query->select('id', 'event_id', 'latitude', 'longitude')
//                    ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(places.latitude)) * cos(radians(places.longitude) - radians(?)) + sin(radians(?)) * sin(radians(places.latitude)))) AS distance', [$latitude, $longitude, $latitude])
//                    ->orderBy('distance')
//                    ->limit(1);
//            }]);
//            $content->with(['places' => function($query) use ($latitude, $longitude) {
//                $query->select('id', 'event_id', 'latitude', 'longitude')
//                    ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$latitude, $longitude, $latitude])
//                    ->orderBy('distance');
//            }])->distinct();
            // Выбираем только первое местоположение для каждого события(Для отладки)
            $content->selectRaw(DB::raw('(SELECT (6371 * acos(cos(radians(?)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(?)) + sin(radians(?)) * sin(radians(p.latitude))))
                          FROM places p WHERE p.event_id = events.id ORDER BY (6371 * acos(cos(radians(?)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(?)) + sin(radians(?)) * sin(radians(p.latitude)))) LIMIT 1) as distance'), [$latitude, $longitude, $latitude, $latitude, $longitude, $latitude])
                ->orderBy('distance')->distinct();

        }
        return $next($content);

    }
}
