<?php

namespace App\Http\Controllers\Api;

use App\Filters\Place\PlaceAddress;
use App\Filters\Place\PlaceDate;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Place\PlaceTypes;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

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
                ])
                ->via('apply')
                ->then(function ($places) {
                    return $places->orderBy('created_at')->get();
                });

            return response()->json(['status' => 'success', 'places' => $response], 200);
        } else {
            return response()->json(['status' => 'error arguments'], 400);
        }
    }

    public function getPlacesIds($id) {
        $place = Place::where('id', $id)->with('event')->get();

        return response()->json($place, 200);
    }
}
