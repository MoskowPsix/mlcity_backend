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
use App\Models\HistoryContent;
use App\Contracts\Services\EventService\EventServiceInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class EventController extends Controller
{
    public function __construct(private readonly EventServiceInterface $eventService)
    {

    }

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

    public function updateVkLikes(Request $request){
        // $request = $request->validated();
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }

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

    //Проверяем лайкал ли авторизованный юзер этот ивент
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $liked = $event->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }

    //Проверяем добавил ли авторизованный юзер этот ивент в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $favorite = $event->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }

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

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->eventService->store($request);
            return response()->json(['status' => 'success',], 200);

        } catch(Exception $e) {
            if ($e->getMessage() == "Is not user organization") {
                return response()->json(['status' => 'error', 'message' => 'Is not user organization'], 403);
            }
            return response()->json(['status' => 'error',], 500);
        }
    }

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

}


