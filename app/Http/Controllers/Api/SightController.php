<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventCity;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventRegion;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Sight\SightTypes;
use App\Http\Controllers\Controller;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class SightController extends Controller
{
    public function getSights(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $sights = Sight::query()->with('types', 'files', 'likes','statuses');

        $response =
            app(Pipeline::class)
                ->send($sights)
                ->through([
                    //фильтры такие же как для ивента, если что то поменяется то надо будет разносить
                    EventLikedUserExists::class,
                    EventFavoritesUserExists::class,
                    EventStatuses::class,
                    EventStatusesLast::class,
                    EventCity::class,
                    EventRegion::class,
                    SightTypes::class,
                    EventGeoPositionInArea::class,
                    EventSearchText::class
                ])
                ->via('apply')
                ->then(function ($sights) use ($pagination , $page, $limit){
                    return $pagination === 'true'
                        ? $sights->orderBy('date_start','desc')->paginate($limit, ['*'], 'page' , $page)
                        : $sights->orderBy('date_start','desc')->get();
                });

        return response()->json(['status' => 'success', 'sights' => $response], 200);
    }
}
