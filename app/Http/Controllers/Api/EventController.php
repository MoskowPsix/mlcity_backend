<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventByIds;
use App\Filters\Event\EventName;
use App\Filters\Event\EventAuthorEmail;
use App\Filters\Event\EventAuthorName;
use App\Filters\Sight\SightAuthor;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventPlaceAddress;
use App\Filters\Event\EventPlaceLocation;
use App\Filters\Event\EventDate;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventPlaceGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Event\EventTypes;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\HistoryContent\HistoryContentLast;
use App\Filters\Event\EventWithPlaceFull;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\Location;
use App\Models\Timezone;
use Illuminate\Pagination\LengthAwarePaginator;

class EventController extends Controller
{
     /**
     * @OA\Get(
     *     path="/events",
     *     tags={"Event"},
     *     summary="Get all events by filters",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="pagination",
     *         description="Pagination on then true",
     *         in="query",
     *         @OA\Schema(
     *             type="bool"
     *         ),
     *     ),
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
     *         description="Event name",
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="statuses",
     *         description="Statuses event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="statusLast",
     *         description="True = last statuses event. False = search by all statuses event",
     *         in="query",
     *         @OA\Schema(
     *             type="bool"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateStart",
     *         description="Date start event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateEnd",
     *         description="Date end event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="eventTypes",
     *         description="Type event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor event",
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
     *         name="user_name",
     *         description="Name user author",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_email",
     *         description="Email user author",
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
     *         description="Region event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         description="Latitude event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         description="Longitude event",
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
    public function getEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $total = 0;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        $events = Event::query()->with('files', 'author', "types", 'price', 'statuses',)->withCount('likedUsers', 'favoritesUsers', 'comments');

        $response =
            app(Pipeline::class)
            ->send($events)
            ->through([
                // EventTotal::class,
                EventOrderByDateCreate::class,
                EventName::class,
                EventByIds::class,
                EventLikedUserExists::class,
                EventFavoritesUserExists::class,
                EventStatuses::class,
                EventStatusesLast::class,
                EventPlaceLocation::class,
                EventDate::class,
                EventTypes::class,
                EventPlaceGeoPositionInArea::class,
                EventSearchText::class,
                EventPlaceAddress::class,
                EventSponsor::class,
                EventAuthorName::class,
                EventAuthorEmail::class,
                SightAuthor::class,
            ])
            ->via('apply')
            ->then(function ($events) use ($page, $limit, $request){
                $events = $events->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);

                return $events;
            });
            // $event['events'] = ['total' => $total ];
        return response()->json(['status' => 'success', 'events' => $response], 200);
    }

    public function getEventsForAuthor(Request $request) {
        isset($request->page) ?  $page = $request->page :  $page = 1;
        isset($request->limit) ?  $limit = $request->limit : $limit =  6;
        $events = Event::where('user_id', auth('api')->user()->id)->with('files', 'author', 'price', 'statuses', 'types')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        $total = $events->count();
        $response = $events->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status' => 'success', 'events' => $response, 'total' => $total], 200);
    }


    //Проверить этот метод
   // public function getUserEvents(Request $request): \Illuminate\Http\JsonResponse
//    {
//        $events = Auth::user()->events()->with('types', 'files', 'statuses')->paginate(10, ['*'], 'page' , $request->page);
//
//        return response()->json([
//             'status' => 'success',
//             'events' => $events
//         ], 200);
//    }
    /**
     * @OA\Post(
     *     path="/events/update-vk-likes",
     *     tags={"Event"},
     *     summary="Update vk likes event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="event_id",
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
        // $request = $request->validated();
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }
    /**
     * @OA\Post(
     *     path="/events/set-event-user-liked",
     *     tags={"Event"},
     *     summary="Set event user liked",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="event_id",
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
    //Создаем отношение - юзер лайкнул ивент
    public function setEvenUserLiked(SetEventUserLikedRequest $request): \Illuminate\Http\JsonResponse{
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id',Auth::user()->id)->exists()){
            $event->likedUsers()->sync(Auth::user()->id);
            $likedUser = true;
        }

        return response()->json(['likedUser' => $likedUser], 200);
    }
    /**
     * @OA\Post(
     *     path="/events/{id}/check-user-liked",
     *     tags={"Event"},
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
    //Проверяем лайкал ли авторизованный юзер этот ивент
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $liked = $event->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }
    /**
     * @OA\Post(
     *     path="/events/{id}/check-user-favorite",
     *     tags={"Event"},
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
    //Проверяем добавил ли авторизованный юзер этот ивент в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $favorite = $event->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }
    /**
     * @OA\Get(
     *     path="/events/{id}",
     *     tags={"Event"},
     *     summary="Get event by id",
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
        $event = Event::query()->where('id', $id)->with('types', 'files','statuses', 'author', 'comments', 'price')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        $response =
        app(Pipeline::class)
        ->send($event)
        ->through([
            EventWithPlaceFull::class
        ])
        ->via("apply")
        ->then(function($event){
            return $event->firstOrFail();
        });
        return response()->json($response, 200);
    }
    public function showForMap($id): \Illuminate\Http\JsonResponse
    {
        $event = Event::where('id', $id)->with('files', 'author', 'price')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments')->firstOrFail();

        return response()->json($event, 200);
    }
    /**
     * @OA\Post(
     *     path="/events/create",
     *     tags={"Event"},
     *     summary="Create new event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="name",
     *         description="Name event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="coords",
     *         description="Coords event <lat, long>",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         description="Description event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         description="Price event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="materials",
     *         in="query",
     *         description="Materials event",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateStart",
     *         description="Date start event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateEnd",
     *         description="Date end event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="vkGroupId",
     *         description="id group vk event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="vkPostId",
     *         description="id post vk event",
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
        // $coords = explode(',',$request->coords);
        // $latitude   = $coords[0]; // широта
        // $longitude  = $coords[1]; // долгота

        $event = Event::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            // 'address'       => $request->address,
            // 'latitude'      => $latitude,
            // 'longitude'     => $longitude,
            'description'   => $request->description,
            'price'         => $request->price,
            'materials'     => $request->materials,
            'date_start'    => $request->dateStart,
            'date_end'      => $request->dateEnd,
            // 'location_id'   => $request->locationId,
            'user_id'       => Auth::user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
        ]);
        // Устанавливаем цену
        foreach ($request->prices as $price){
            if($price["cost_rub"] == ""){
                $event->price()->create([
                    'cost_rub' => 0,
                    'descriptions' => $price['descriptions']
                ]);
            }
            else{
                $event->price()->create([
                    'cost_rub' => $price['cost_rub'],
                    'descriptions' => $price['descriptions']
                ]);
            }
        }
        // Устанавливаем марки
        foreach ($request->places as $place){
            $coords = explode(',',$place['coords']);
            $latitude   = $coords[0]; // широта
            $longitude  = $coords[1]; // долгота
            $timezone_id = Timezone::where('name', Location::find($place['locationId'])->time_zone)->first()->id;
            $place_cr = $event->places()->create([
                'sight_id' => $place['sightId'],
                'location_id' => $place['locationId'],
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $place['address'],
                'timezone_id' => $timezone_id
            ]);
            // Устанавливаем сеансы марок

            foreach($place['seances'] as $seance) {
                info($seance);
                $sean_cr = $place_cr->seances()->create([
                    'date_start' => $seance['dateStart'],
                    'date_end' => $seance['dateEnd']
                ]);

            }
        }

        $types = explode(",",$request->type[0]);
        // info($types);
        $event->types()->sync($types);
        $event->statuses()->attach($request->status, ['last' => true]);
        $event->likes()->create();
//        $event->likes()->create([
//            "vk_count" => $request->vkLikesCount ? $request->vkLikesCount : 0,
//        ]);


        if ($request->vkFilesImg){
            $this->saveVkFilesImg($event, $request->vkFilesImg);
        }

        if ($request->vkFilesVideo){
            $this->saveVkFilesVideo($event, $request->vkFilesVideo);
        }
        if ($request->vkFilesLink){
            $this->saveVkFilesLink($event, $request->vkFilesLink);
        }

        if ($request->localFilesImg){
            $this->saveLocalFilesImg($event, $request->localFilesImg);
        }

        return response()->json(['status' => 'success',], 200);
    }
    /**
     * @OA\Get(
     *     path="/events/{id}/liked-users",
     *     tags={"Event"},
     *     summary="Get users like event",
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
    public function getEventUserLikedIds($id, PageANDLimitRequest $request): \Illuminate\Http\JsonResponse
    {
        $likedUsers = Event::findOrFail($id)->likedUsers;
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
     *     path="/events/{id}/favorites-users",
     *     tags={"Event"},
     *     summary="Get users like event",
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
    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): \Illuminate\Http\JsonResponse
    {
        $likedUsers = Event::findOrFail($id)->favoritesUsers;
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
     *     path="/updateEvent/{id}",
     *     tags={"Event"},
     *     summary="Update event by id",
     *     security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         description="id event",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="Name event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sponsor",
     *         description="Sponsor event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         description="City event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         description="Address event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="latitude",
     *         description="Latitude event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         description="Longitude event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         description="Description event",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         description="Price event",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="materials",
     *         in="query",
     *         description="Materials event",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateStart",
     *         description="Date start event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateEnd",
     *         description="Date end event",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
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
    public function updateEvent(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $event = Event::where('id', $id)->firstOrFail();
        $event->fill($data);
        $event->save();

        $jsonData = [
            'status' => 'SUCCESS',
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'sponsor' => $event->sponsor,
                'location_id' => $event->location_id,
                'address' => $event->address,
                'description' => $event->description,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'price' => $event->price,
                'materials' => $event->materials,
                'date_start' => $event->date_start,
                'date_end' => $event->date_end,

            ]
        ];

        return response()->json($jsonData);
    }

    public function getHistoryContent(Request $request, $id)
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;

        $historyContent = HistoryContent::query()->where("history_contentable_id", $id)->where("history_contentable_type", "App\Models\Event");

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

    private function saveVkFilesImg($event, $files){
        $type = FileType::where('name', 'image')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ])->file_types()->attach($type[0]->id);
        }
    }
    private function saveVkFilesVideo($event, $files){
        $type = FileType::where('name', 'video')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('video_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveVkFilesLink($event, $files){
        $type = FileType::where('name', 'link')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('link_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveLocalFilesImg($event, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_');

            $path = $file->store('events/'.$event->id, 'public');

            $type = FileType::where('name', 'image')->get();

            $event->files()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ])->file_types()->sync($type[0]->id);

        }

    }
}


