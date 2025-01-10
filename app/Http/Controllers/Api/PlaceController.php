<?php

namespace App\Http\Controllers\Api;

use App\Filters\Place\PlaceAddress;
use App\Filters\Place\PlaceDate;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Place\PlaceIco;
use App\Filters\Place\PlaceIds;
use App\Filters\Place\PlaceTypes;
use App\Filters\Place\PlaceStatuses;
use App\Filters\Place\PlaceLocation;
use App\Filters\Place\PlaceStatusesLast;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Place;
use Elastic\Elasticsearch\Client;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{

    public function __construct(private Client $elasticsearch) {
        $this->elasticsearch = $elasticsearch;
    }

    public function getPlaces(Request $request): \Illuminate\Http\JsonResponse
    {
        if (request()->has('radius') && ($request->radius <= 25) && (request()->get('latitude') && request()->get('longitude'))) {

            $places = Place::query();
            $response =
                app(Pipeline::class)
                ->send($places)
                ->through([
                    PlaceGeoPositionInArea::class,
                    PlaceAddress::class,
                    PlaceDate::class,
                    PlaceTypes::class,
                    PlaceStatusesLast::class,
                    PlaceStatuses::class,
                    PlaceIco::class,
                    // PlaceIds::class,
                ])
                ->via('apply')
                ->then(function ($places) {
                    $places = $places->get();
                    foreach ($places as $key => $place) {
                        $places[$key]->ico = count($place->event->types) ?  $place->event->types[0]->ico : null;
                        unset($places[$key]->event);
                    }
                    return $places;
                });

            return response()->json(['status' => 'success', 'places' => $response], 200);
        } else {
            return response()->json(['status' => 'error arguments'], 400);
        }
    }

    public function getPlacesIds($id)
    {
        $place = Place::where('id', $id)->with('eventWithLikes')->firstOrFail();

        $place->event = $place["eventWithLikes"];

        unset($place->eventWithLikes);

        return response()->json(['status' => 'success', 'places' => $place], 200);
    }
    public function getPlacesAtEventIds($id, Request $request)
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 5;

        $places = Event::find($id)->places()->with('seances');

        $response =
            app(Pipeline::class)
            ->send($places)
            ->through([
                PlaceLocation::class,
            ])
            ->via('apply')
            ->then(function ($places) use ($limit, $page) {
                $places = $places->with('location')->orderBy('created_at', 'desc')->cursorPaginate($limit, ['*'], 'page', $page);
                return $places;
            });

        // $place = Event::where('id', $id)->first()->places()->with('location')->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status' => 'success', 'places' => $response], 200);
    }

    public function getEventsElastic(Request $request) {
        $page = 2;
        $limit = 10;
        $from = $page * $limit;
        $model = new Place();
        $searchParams = [
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            '_source' => ['id', 'event_id', 'location'],
            'size' => 100,
            'body' => [
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "nested" => [
                                    "path" => "seances",
                                    "query" => [
                                        "bool" => [
                                            "must" => [
                                                [
                                                    "range" => [
                                                        "seances.date_end" => [
                                                            "gte" => $request->date_start, // Начальная дата
                                                            "lte" => $request->date_end // Конечная дата
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "nested" => [
                                    "path" => "status",
                                    "query" => [
                                        "bool" => [
                                            "must" => [
                                                [
                                                    "match" => [
                                                        "status.name" => $request->statusName
                                                    ]
                                                ],
                                                [
                                                    "term" => [
                                                        "status.last" => $request->statusLast
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "sort" => [
                    '_geo_distance' => [
                        'location' => [
                            'lat' => $request->latitude,
                            'lon' => $request->longitude
                        ],
                        'order' => 'asc',
                        'unit' => 'km',
                        'distance_type' => 'arc',
                    ]
                ]
            ],
        ];

        $items = $this->elasticsearch->search($searchParams)->asArray();
        dd($items);
        foreach ($items['hits']['hits'] as $item) {
            $ids[] = $item['_source']['event_id'];
            $distance[$item['_source']['event_id']] = $item['sort'][0];
        }
        $response = Event::whereIn('id', array_unique($ids))->get();
        foreach ($response as $key => $event) {
            $response[$key]->distance = $distance[$event->id];
        }
        return response()->json($response);
    }
}
