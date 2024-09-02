<?php

namespace App\Http\Controllers\Api;

use App\Filters\Sight\SightLocation;
use App\Filters\Place\PlaceAddress;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\Event\EventName;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventRegion;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\HistoryContent\HistoryContentLast;
use App\Filters\Sight\SightTypes;
use App\Filters\Sight\SightAuthor;
use App\Filters\Sight\SightByIds;
use App\Filters\Sight\SightEvents;
use App\Filters\Sight\SightIco;
use App\Http\Controllers\Controller;
use App\Http\Requests\SightCreateRequest;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\Status;

class SightController extends Controller
{

    public function getSights(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;

        $sights = Sight::query()->with('files', 'author', 'locations', 'statuses', 'types')->withCount('likedUsers', 'favoritesUsers', 'comments');

        $response =
            app(Pipeline::class)
                ->send($sights)
                ->through([
                    //фильтры такие же как для местоа, если что то поменяется то надо будет разносить
                    EventOrderByDateCreate::class,
                    EventLikedUserExists::class,
                    EventName::class,
                    EventSponsor::class,
                    EventFavoritesUserExists::class,
                    EventStatuses::class,
                    EventStatusesLast::class,
                    SightLocation::class,
                    PlaceAddress::class,
                    SightTypes::class,
                    SightAuthor::class,
                    PlaceGeoPositionInArea::class,
                    EventSearchText::class,
                    SightByIds::class
                ])
                ->via('apply')
                ->then(function ($sights) use ($pagination , $page, $limit){
                    $sights = $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                    return $sights;
                });

        return response()->json(['status' => 'success', 'sights' => $response], 200);
    }

    public function getSightsForMap(Request $request): \Illuminate\Http\JsonResponse
    {
        if (request()->has('radius') && ($request->radius <= 25) && (request()->get('latitude') && request()->get('longitude'))) {
            $sights = Sight::query();
            $response =
                app(Pipeline::class)
                    ->send($sights)
                    ->through([
                        //фильтры такие же как для местоа, если что то поменяется то надо будет разносить
                        EventStatuses::class,
                        EventStatusesLast::class,
                        SightLocation::class,
                        PlaceAddress::class,
                        SightTypes::class,
                        PlaceGeoPositionInArea::class,
                        SightIco::class
                    ])
                    ->via('apply')
                    ->then(function ($sights) {
                        return $sights->orderBy('created_at','desc')->get();
                    });

            return response()->json(['status' => 'success', 'sights' => $response], 200);
        } else {
            return response()->json(['status' => 'error arguments'], 400);
        }
    }
    public function getSightsForAuthor(Request $request) {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        $sights = Sight::where('user_id', auth('api')->user()->id)->with('files', 'author', 'statuses', 'types')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        $total = $sights->count();
        $response = $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status' => 'success', 'sights' => $response, 'total' => $total], 200);
    }
    public function showForCard($id): \Illuminate\Http\JsonResponse
    {
        $sight = Sight::where('id', $id)->with('files', 'author')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments')->firstOrFail();

        return response()->json($sight, 200);
    }

    public function updateVkLikes(Request $request){
        $sight = Sight::find($request->sight_id);
        $sight->likes()->update(['vk_count' => $request->likes_count]);
    }

    //Создаем отношение - юзер лайкнул место
    public function setSightUserLiked(Request $request): \Illuminate\Http\JsonResponse{
        $sight = Sight::find($request->sight_id);
        $likedUser = false;

        if (!$sight->likedUsers()->where('user_id',Auth::user()->id)->exists()){
            $sight->likedUsers()->sync(Auth::user()->id);
            $likedUser = true;
        }

        return response()->json(['likedUser' => $likedUser], 200);
    }

    //Проверяем лайкал ли авторизованный юзер этот место
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Sight::where('id', $id)->firstOrFail();

        $liked = $sight->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }

    //Проверяем добавил ли авторизованный юзер этот место в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Sight::where('id', $id)->firstOrFail();

        $favorite = $sight->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $sight = Sight::where('id', $id)->with('types', 'files', 'likes','statuses', 'author', 'comments', 'locations', 'prices');
        $response =
        app(Pipeline::class)
        ->send($sight)
        ->through([
            SightEvents::class
        ])
        ->via("apply")
        ->then(function ($sight){
            $sight = $sight->get();
            return $sight[0];
        });
        return response()->json($response, 200);
    }

    public function getEventsInSight(Request $request, $id){
        $sight = Sight::find($id);
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        if($sight){
            $events = $sight->events()->cursorPaginate($limit, ['*'], 'page' , $page);

            return response()->json(['status' => 'success', 'events' => $events], 200);
        }

        return response()->json(["message" => "Sight not found"], 404);
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = Status::where("name", "Опубликовано")->get()->first();
        $coords = explode(',',$request->coords);
        $latitude   = $coords[0]; // широта
        $longitude  = $coords[1]; // долгота

        $sight = Sight::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            'location_id'   => $request->locationId,
            'address'       => $request->address,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'description'   => $request->description,
            // 'price'         => $request->price,
            'materials'     => $request->materials,
            'user_id'       => auth("api")->user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
            'organization_id' => 1,
            // 'work_time'     => $request->workTime,
        ]);

        $sight->organization()->create();

        foreach ($request->price as $price){
            if($price["cost_rub"] == ""){
                $sight->prices()->create([
                    'cost_rub' => 0,
                    'descriptions' => $price['descriptions']
                ]);
            }
            else{
                $sight->prices()->create([
                    'cost_rub' => $price['cost_rub'],
                    'descriptions' => $price['descriptions']
                ]);
            }

        }
        $types = explode(",",$request->type[0]);
        // info($types);
        $sight->types()->sync($types);
        $sight->statuses()->attach($status->id, ['last' => true]);
        $sight->likes()->create();
//        $sight->likes()->create([
//            "vk_count" => $request->vkLikesCount ? $request->vkLikesCount : 0,
//        ]);

        \Illuminate\Support\Facades\Log::info('Файлы'.$request);
        if ($request->vkFilesImg){
            $this->saveVkFilesImg($sight, $request->vkFilesImg);
        }

        if ($request->vkFilesVideo){
            $this->saveVkFilesVideo($sight, $request->vkFilesVideo);
        }
        if ($request->vkFilesLink){
            $this->saveVkFilesLink($sight, $request->vkFilesLink);
        }

        if ($request->localFilesImg){
            $this->saveLocalFilesImg($sight, $request->localFilesImg);
        }

        return response()->json(['status' => 'success', 'sight' => $sight], 201);
    }

    public function getSightUserLikedIds($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $likedUsers = Sight::findOrFail($id)->likedUsers;
        $likedUsersIds = [];

        foreach ($likedUsers as $user){
            $likedUsersIds[] = $user;
        }
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $paginator = new LengthAwarePaginator($likedUsersIds, count($likedUsersIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
            'status' => 'success',
            'result' => $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
       ], 200);
    }

    public function getSightUserFavoritesIds($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $likedUsers = Sight::findOrFail($id)->favoritesUsers;
        $likedUsersIds = [];

        foreach ($likedUsers as $user){
            $likedUsersIds[] = $user;
        }
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $paginator = new LengthAwarePaginator($likedUsersIds, count($likedUsersIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
            'status' => 'success',
            'result' => $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
       ], 200);
    }

    public function updateSight(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $sight = Sight::where('id', $id)->firstOrFail();
        $sight->fill($data);
        $sight->save();

        $jsonData = [
            'status' => 'SUCCESS',
            'sight' => [
                'id' => $sight->id,
                'name' => $sight->name,
                'sponsor' => $sight->sponsor,
                'location' => $sight->location_id,
                'address' => $sight->address,
                'description' => $sight->description,
                'latitude' => $sight->latitude,
                'longitude' => $sight->longitude,
                'price' => $sight->price,
                'materials' => $sight->materials,
                'date_start' => $sight->date_start,
                'date_end' => $sight->date_end,
                'vk_group_id'   => $request->vk_group_id,
                'vk_post_id'    => $request->vk_post_id,
            ]
        ];

        return response()->json($jsonData);
    }
    public function getHistoryContent(Request $request, $id)
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;

        $historyContent = HistoryContent::query()->where("history_contentable_id", $id)->where("history_contentable_type", "App\Models\Sight");

        $response =
        app(Pipeline::class)
        ->send($historyContent)
        ->through([
            HistoryContentLast::class
        ])
        ->via("apply")
        ->then(function($historyContent) use ($pagination , $page, $limit) {

            if(request()->get("last") == true)
            {
                $res = $historyContent->get()->first();
            }
            else {
                $res = $historyContent->cursorPaginate($limit, ['*'], 'page' , $page);
            }
            return $res;
        });

        return response()->json(["status"=>"success", "history_content" => $response]);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        dd('delete');
    }

    private function saveVkFilesImg($sight, $files){
        $type = FileType::where('name', 'image')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ])->file_types()->attach($type[0]->id);
        }

    }
    private function saveVkFilesVideo($sight, $files){
        $type = FileType::where('name', 'video')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('video_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveVkFilesLink($sight, $files){
        $type = FileType::where('name', 'link')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('link_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }

    private function saveLocalFilesImg($sight, $files){
        foreach ($files as $file) {
            $filename = uniqid('img_');

            $type = FileType::where('name', 'image')->get();

            $path = $file->store('sights/'.$sight->id, 'public');

            $sight->files()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ])->file_types()->sync($type[0]->id);

        }

    }
}
