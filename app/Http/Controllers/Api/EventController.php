<?php

namespace App\Http\Controllers\Api;

use App\Filters\HistoryContent\HistoryContentLast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\EventForAuthorReqeust;
use App\Http\Requests\Events\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Resources\Event\CreateEvent\ErrorAuthCreateEventResource;
use App\Http\Resources\Event\CreateEvent\ErrorCreateEventResource;
use App\Http\Resources\Event\CreateEvent\SuccessCreateEventResource;
use App\Http\Resources\Event\GetEventForAuthor\SuccessGetEventForAuthorResource;
use App\Http\Resources\Event\GetEvents\SuccessGetEventsResource;
use App\Http\Resources\Event\GetEventUserFavoritesIds\SuccessGetEventUserFavoritesIdsResource;
use App\Http\Resources\Event\GetEventUserLikedIds\SuccessGetEventUserLikedIdsResource;
use App\Http\Resources\Event\SetEventUserLiked\SuccessSetEventUserLikedResource;
use App\Http\Resources\Event\ShowEvent\SuccessShowEventResource;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Models\Event;
use App\Models\HistoryContent;
use App\Contracts\Services\EventService\EventServiceInterface;
use App\Http\Requests\Events\GetEventRequest;
use App\Http\Requests\Events\ShowEventRequest;
use App\Http\Resources\Event\CheckFavoriteEvent\SuccessCheckFavoriteEventLikedResource;
use App\Http\Resources\Event\CheckLikedEvent\SuccessCheckLikedEventLikedResource;
use App\Http\Resources\Event\ShowForMapEvent\SuccessShowForMapEventResource;
use Exception;

#[Group(name: 'Events', description: 'События')]
class EventController extends Controller
{
    public function __construct(private readonly EventServiceInterface $eventService)
    {}

    #[ResponseFromApiResource(SuccessGetEventsResource::class)]
    #[Endpoint(title: 'getEvents', description: 'Возвращает все события по фильтрам')]
    public function getEvents(GetEventRequest $request): SuccessGetEventsResource
    {
        $response = $this->eventService->get($request);
        return new SuccessGetEventsResource($response);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessGetEventForAuthorResource::class)]
    #[Endpoint(title: 'getEventsForAuthor', description: 'Возвращает события пользователя')]
    public function getEventsForAuthor(EventForAuthorReqeust $request): SuccessGetEventForAuthorResource
    {
        $response = $this->eventService->getUserEvents($request);
        return new SuccessGetEventForAuthorResource($response);
    }


    #[NoReturn]
    #[Endpoint(title: 'updateVkLikes', description: 'Обновляет лайки, подтягивая их с вк. Ничего не возвращает')]
    public function updateVkLikes(Request $request): void
    {
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessSetEventUserLikedResource::class)]
    #[Endpoint(title: 'setEvenUserLiked', description: 'Создаем отношение - юзер лайкнул ивент')]
    public function setEvenUserLiked(SetEventUserLikedRequest $request): SuccessSetEventUserLikedResource
    {
        $likedUser = $this->eventService->setEvenUserLiked($request);
        return new SuccessSetEventUserLikedResource($likedUser);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessCheckLikedEventLikedResource::class)]
    #[Endpoint(title: 'checkLikedEventForUser', description: 'Проверка лайкал ли авторизованный юзер этот ивент')]
    public function checkLiked(int $id): SuccessCheckLikedEventLikedResource
    {
        $liked = $this->eventService->checkLiked($id);
        return  new SuccessCheckLikedEventLikedResource($liked);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessCheckFavoriteEventLikedResource::class)]
    #[Endpoint(title: 'checkFavoriteEventForUser', description: 'Проверка добавил ли авторизованный юзер этот ивент в избранное')]
    public function checkFavorite($id): SuccessCheckFavoriteEventLikedResource
    {
        $favorite = $liked = $this->eventService->checkFavorite($id);
        return  new SuccessCheckFavoriteEventLikedResource($favorite);
    }


    #[ResponseFromApiResource(SuccessShowEventResource::class, Event::class)]
    #[Endpoint(title: 'getEvent', description: 'Достать события по id')]
    public function show(ShowEventRequest $request, int $id): SuccessShowEventResource
    {
        $response = $this->eventService->getById($id);
        return new SuccessShowEventResource($response);
    }


    #[ResponseFromApiResource(SuccessShowForMapEventResource::class)]
    #[Endpoint(title: 'getEventForMap', description: 'Достать событие по id для карты')]
    public function showForMap(int $id): SuccessShowForMapEventResource
    {
        $response = $this->eventService->showForMap($id);
        return new SuccessShowForMapEventResource($response);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessCreateEventResource::class)]
    #[ResponseFromApiResource(ErrorCreateEventResource::class, null, 500)]
    #[ResponseFromApiResource(ErrorAuthCreateEventResource::class, null, 403)]
    #[Endpoint(title: 'createEvent', description: 'Создание события')]
    public function create(EventCreateRequest $request): SuccessCreateEventResource | ErrorCreateEventResource | ErrorAuthCreateEventResource
    {
        try {
            $this->eventService->store($request);
            return new SuccessCreateEventResource([]);

        } catch(Exception $e) {
            if ($e->getMessage() == "Is not user organization") {
                return new ErrorAuthCreateEventResource([]);
            }
            return new ErrorCreateEventResource([]);
        }
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessGetEventUserLikedIdsResource::class)]
    #[Endpoint(title: 'getEventUserLikedIds', description: 'Получить пользователей которые лайкали событие')]
    public function getEventUserLikedIds(int $id, PageANDLimitRequest $request): SuccessGetEventUserLikedIdsResource
    {
        $events = $this->eventService->getEventUserLiked($id, $request);
        return new SuccessGetEventUserLikedIdsResource($events);
    }


    #[Authenticated]
    #[ResponseFromApiResource(SuccessGetEventUserFavoritesIdsResource::class)]
    #[Endpoint(title: 'getEventUserLikedIds', description: 'Получить пользователей которые лайкали событие')]
    public function getEventUserFavoritesIds(int $id, PageANDLimitRequest $request): SuccessGetEventUserFavoritesIdsResource
    {
        $events = $this->eventService->getEventUserLiked($id, $request);
        return new SuccessGetEventUserFavoritesIdsResource($events);
    }


    #[Authenticated]
    #[Endpoint(title: 'getHistoryContent', description: 'Получить объект истории. Метод доступен только модерам.')]
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

    public function getOrganizationOfEvent($id)
    {
        $organization = $this->eventService->getOrganizationOfEvent($id);

        return response()->json(["status"=>"success", "organization" => $organization]);
    }
}


