<?php

namespace App\Http\Controllers\Api;

use App\Filters\Sight\SightLocation;
use App\Filters\Place\PlaceAddress;
use App\Filters\Event\EventName;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventRegion;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Sight\SightTypes;
use App\Filters\Sight\SightAuthor;
use App\Http\Controllers\Controller;
use App\Http\Requests\SightCreateRequest;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\FileType;

class SightController extends Controller
{
             /**
     * @OA\Get(
     *     path="/sights",
     *     tags={"Sight"},
     *     summary="Get all sights by filters",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Sight name",
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="statuses",
     *         description="Statuses sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="statusLast",
     *         description="True = last statuses event. False = search by all statuses sight",
     *         in="query",
     *         @OA\Schema(
     *             type="bool"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sightTypes",
     *         description="Type sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="searchText",
     *         description="Search text by name event, sponsor event and description event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user",
     *         description="Email and name user author",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="region",
     *         description="Region sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         description="Latitude sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         description="Longitude sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function getSights(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;

        $sights = Sight::query()->with('files', 'author', 'locations')->withCount('likedUsers', 'favoritesUsers', 'comments');

        $response =
            app(Pipeline::class)
                ->send($sights)
                ->through([
                    //фильтры такие же как для местоа, если что то поменяется то надо будет разносить
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
                    EventSearchText::class
                ])
                ->via('apply')
                ->then(function ($sights) use ($pagination , $page, $limit){
                    $total = $sights->count();
                    $sights = $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                    return [$sights, $total];
                });

        return response()->json(['status' => 'success', 'sights' => $response[0], 'total' => $response[1]], 200);
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
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;
        $sights = Sight::where('user_id', auth('api')->user()->id)->with('files', 'author')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        $total = $sights->count();
        $response = $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status' => 'success', 'sights' => $response, 'total' => $total], 200);
    }
    public function showForCard($id): \Illuminate\Http\JsonResponse
    {
        $sight = Sight::where('id', $id)->with('files', 'author')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments')->firstOrFail();

        return response()->json($sight, 200);
    }
    /**
     * @OA\Post(
     *     path="/sights/update-vk-likes",
     *     tags={"Sight"},
     *     summary="Update vk likes sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="sight_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="likes_count",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function updateVkLikes(Request $request){
        $sight = Sight::find($request->sight_id);
        $sight->likes()->update(['vk_count' => $request->likes_count]);
    }
     /**
     * @OA\Post(
     *     path="/sights/set-sight-user-liked",
     *     tags={"Sight"},
     *     summary="Set sight user liked",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="sight_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/sights/{id}/check-user-liked",
     *     tags={"Sight"},
     *     summary="Check user liked",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    //Проверяем лайкал ли авторизованный юзер этот место
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Sight::where('id', $id)->firstOrFail();

        $liked = $sight->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }
    /**
     * @OA\Post(
     *     path="/sights/{id}/check-user-favorite",
     *     tags={"Sight"},
     *     summary="Check user favorite",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    //Проверяем добавил ли авторизованный юзер этот место в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Sight::where('id', $id)->firstOrFail();

        $favorite = $sight->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }
    /**
     * @OA\Get(
     *     path="/sights/{id}",
     *     tags={"Sight"},
     *     summary="Get sight by id",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $sight = Sight::where('id', $id)->with('types', 'files', 'likes','statuses', 'author', 'comments', 'locations', 'prices')->firstOrFail();

        return response()->json($sight, 200);
    }
    /**
     * @OA\Post(
     *     path="/sights/create",
     *     tags={"Sight"},
     *     summary="Create new sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="name",
     *         description="Name sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="coords",
     *         description="Coords sight <lat, long>",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         description="Description sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         description="Price sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="materials",
     *         in="query",
     *         description="Materials sight",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="vkGroupId",
     *         description="id group vk sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="vkPostId",
     *         description="id post vk sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
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
            'user_id'       => Auth::user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
            'work_time'     => $request->workTime,
        ]);

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
        $sight->types()->sync($request->type);
        $sight->statuses()->attach($request->status, ['last' => true]);
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
    /**
     * @OA\Get(
     *     path="/sights/{id}/liked-users",
     *     tags={"Sight"},
     *     summary="Get users like sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/sights/{id}/favorites-users",
     *     tags={"Sight"},
     *     summary="Get users like sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Put(
     *     path="/sights/updateSight/{id}",
     *     tags={"Sight"},
     *     summary="Update sight by id",
     *     security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         description="id sight",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="Name sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         description="Latitude sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         description="Longitude sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         description="Description sight",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         description="Price sight",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="materials",
     *         in="query",
     *         description="Materials sight",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
