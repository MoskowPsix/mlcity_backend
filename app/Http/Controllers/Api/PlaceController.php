<?php

namespace App\Http\Controllers\Api;

use App\Filters\Place\PlaceAddress;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function getPlaces (Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $places = Place::query()->with('event');

        $response =
            app(Pipeline::class)
            ->send($places)
            ->through([
                PlaceGeoPositionInArea::class,
                PlaceAddress::class,
            ])
            ->via('apply')
            ->then(function ($places) use ($pagination , $page, $limit) {
                return $pagination === 'true'
                    ? $places->orderBy('created_at')->paginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'))
                    : $places->orderBy('created_at')->get();
            });

        return response()->json(['status' => 'success', 'places' => $response], 200);
    }
}