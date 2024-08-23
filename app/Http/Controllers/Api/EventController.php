<?php

namespace App\Http\Controllers\Api;

use App\Filters\HistoryContent\HistoryContentLast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
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
        $response = $this->eventService->get($request);
        return response()->json(['status' => 'success', 'events' => $response], 200);
    }

    public function getEventsForAuthor(Request $request) {
        $response = $this->eventService->getUserEvents($request);
        return response()->json(['status' => 'success', 'events' => $response], 200);
    }

    public function updateVkLikes(Request $request){
        // $request = $request->validated();
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }

    //Создаем отношение - юзер лайкнул ивент
    public function setEvenUserLiked(SetEventUserLikedRequest $request): \Illuminate\Http\JsonResponse{
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id',auth('api')->user()->id)->exists()){
            $event->likedUsers()->sync(auth('api')->user()->id);
            $likedUser = true;
        }

        return response()->json(['likedUser' => $likedUser], 200);
    }

    //Проверяем лайкал ли авторизованный юзер этот ивент
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $liked = $event->likedUsers()->where('user_id', auth('api')->user()->id)->exists();

        return  response()->json($liked, 200);
    }

    //Проверяем добавил ли авторизованный юзер этот ивент в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $favorite = $event->favoritesUsers()->where('user_id', auth('api')->user()->id)->exists();

        return  response()->json($favorite, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->eventService->getById($id);
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
            'event' => $event
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
}


