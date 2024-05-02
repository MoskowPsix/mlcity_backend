<?php

namespace App\Http\Controllers\Api;

use App\Filters\Place\PlaceAddress;
use App\Filters\Place\PlaceDate;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Place\PlaceIco;
use App\Filters\Place\PlaceTypes;
use App\Filters\Place\PlaceStatuses;
use App\Filters\Place\PlaceLocation;
use App\Filters\Place\PlaceStatusesLast;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Place;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    public function getPlaces (Request $request): \Illuminate\Http\JsonResponse
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
                    PlaceIco::class
                ])
                ->via('apply')
                ->then(function ($places) {
                    $places = $places->get();

                    foreach($places as $key=>$place){
                        $places[$key]->ico = $place->event->types[0]->ico;
                        unset($places[$key]->event);
                    }
                    // foreach($places as $key=>$place){
                    //     $type_id = DB::table("events_etypes")->where("event_id","=",$place->event_id)->first()->etype_id;
                    //     $ico = EventType::find($type_id)->ico;
                    //     $places[$key]->ico = $ico;
                    // }
                    return $places;
                });

            return response()->json(['status' => 'success', 'places' => $response], 200);
        } else {
            return response()->json(['status' => 'error arguments'], 400);
        }
    }

    public function getPlacesIds($id) {
        $place = Place::where('id', $id)->with('eventWithLikes')->firstOrFail();

        $place->event = $place["eventWithLikes"];

        unset($place->eventWithLikes);

        return response()->json(['status' => 'success', 'places' => $place], 200);
    }
    public function getPlacesAtEventIds($id,Request $request) {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;

        $places = Event::find($id)->places();

        $response =
                app(Pipeline::class)
                ->send($places)
                ->through([
                    PlaceLocation::class,
                ])
                ->via('apply')
                ->then(function ($places) use($limit, $page) {
                    $places = $places->with('location')->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                    return $places;
                });

        // $place = Event::where('id', $id)->first()->places()->with('location')->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status'=> 'success','places'=> $response], 200);
    }
}
