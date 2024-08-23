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
use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\GetEventRequest;
use App\Http\Requests\Events\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Resources\Event\CheckLikedAndFavoriteResource;
use App\Http\Resources\Event\EventCreateResource;
use App\Http\Resources\Event\EventResource;
use App\Http\Resources\Event\EventUserLikedOrFavoriteIdsResource;
use App\Http\Resources\Event\GetEventsResource;
use App\Http\Resources\Event\SetEventUserLikedResource;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\Location;
use App\Models\Timezone;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\NoReturn;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group(name: 'Events', description: 'События')]
class EventController extends Controller
{
    #[ResponseFromApiResource(GetEventsResource::class)]
    #[Endpoint(title: 'getEvents', description: 'Возвращает все события по фильтрам')]
    public function getEvents(GetEventRequest $request): mixed
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        $events = Event::with('files', 'author', "types", 'price', 'statuses',)->withCount('likedUsers', 'favoritesUsers');

        $response =
            app(Pipeline::class)
            ->send($events)
            ->through([
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
                return $events->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
            });
        return new GetEventsResource($response);
    }
    #[Authenticated]
    #[ResponseFromApiResource(GetEventsResource::class)]
    #[Endpoint(title: 'getEventsForAuthor', description: 'Возвращает события пользователя')]
    public function getEventsForAuthor(Request $request): \Illuminate\Http\JsonResponse
    {
        isset($request->page) ?  $page = $request->page :  $page = 1;
        isset($request->limit) ?  $limit = $request->limit : $limit =  6;
        $events = Event::where('user_id', auth('api')->user()->id)->with('files', 'author', 'price', 'statuses', 'types')->withCount('likedUsers', 'favoritesUsers');
        $total = $events->count();
        $response = $events->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(['status' => 'success', 'events' => $response, 'total' => $total], 200);
    }

    #[NoReturn]
    #[Endpoint(title: 'updateVkLikes', description: 'Обновляет лайки, подтягивая их с вк. Ничего не возвращает')]
    public function updateVkLikes(Request $request): void
    {
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }

    #[Authenticated]
    #[ResponseFromApiResource(SetEventUserLikedResource::class)]
    #[Endpoint(title: 'setEvenUserLiked', description: 'Создаем отношение - юзер лайкнул ивент')]
    public function setEvenUserLiked(SetEventUserLikedRequest $request): SetEventUserLikedResource
    {
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id',Auth::user()->id)->exists()){
            $event->likedUsers()->sync(Auth::user()->id);
            $likedUser = true;
        }

        return new SetEventUserLikedResource($likedUser);
    }

    #[Authenticated]
    #[ResponseFromApiResource(CheckLikedAndFavoriteResource::class)]
    #[Endpoint(title: 'checkLikedEventForUser', description: 'Проверка лайкал ли авторизованный юзер этот ивент')]
    public function checkLiked($id): CheckLikedAndFavoriteResource
    {
        $event =  Event::where('id', $id)->firstOrFail();
        $liked = $event->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  new CheckLikedAndFavoriteResource($liked);
    }

    #[Authenticated]
    #[ResponseFromApiResource(CheckLikedAndFavoriteResource::class)]
    #[Endpoint(title: 'checkFavoriteEventForUser', description: 'Проверка добавил ли авторизованный юзер этот ивент в избранное')]
    public function checkFavorite($id): CheckLikedAndFavoriteResource
    {
        $event =  Event::where('id', $id)->firstOrFail();
        $favorite = $event->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  new CheckLikedAndFavoriteResource($favorite);
    }

    #[ResponseFromApiResource(EventResource::class, Event::class)]
    #[Endpoint(title: 'getEvent', description: 'Достать события по id')]
    public function show(int $id): EventResource
    {
        $event = Event::where('id', $id)->with('types', 'files','statuses', 'author', 'price')->withCount( 'likedUsers', 'favoritesUsers');
        $response =
        app(Pipeline::class)
        ->send($event)
        ->through([
            EventWithPlaceFull::class
        ])
        ->via("apply")
        ->then(function($event){
            return $event->first();
        });
        return new EventResource($response);
    }
    
    #[ResponseFromApiResource(EventResource::class)]
    #[Endpoint(title: 'getEventForMap', description: 'Достать событие по id для карты')]
    public function showForMap($id): EventResource
    {
        $event = Event::where('id', $id)->with('files', 'author', 'price')->withCount('likedUsers', 'favoritesUsers')->firstOrFail();

        return new EventResource($event);
    }
    #[Authenticated]
    #[ResponseFromApiResource(EventCreateResource::class)]
    #[Endpoint(title: 'createEvent', description: 'Создание события')]
    public function create(EventCreateRequest $request): EventCreateResource
    {
        $event = Event::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            'description'   => $request->description,
            'price'         => $request->price,
            'materials'     => $request->materials,
            'date_start'    => $request->dateStart,
            'date_end'      => $request->dateEnd,
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
        $event->types()->sync($types);
        $event->statuses()->attach($request->status, ['last' => true]);
        $event->likes()->create();

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

        return new EventCreateResource([]);
    }
    #[Authenticated]
    #[ResponseFromApiResource(EventUserLikedOrFavoriteIdsResource::class)]
    #[Endpoint(title: 'getEventUserLikedIds', description: 'Получить пользователей которые лайкали событие')]
    public function getEventUserLikedIds($id, PageANDLimitRequest $request): EventUserLikedOrFavoriteIdsResource
    {
        $likedUsers = Event::findOrFail($id)->likedUsers;
        return new EventUserLikedOrFavoriteIdsResource($this->getUsersLikedOrFavorite($request, $likedUsers));
    }
    #[Authenticated]
    #[ResponseFromApiResource(EventUserLikedOrFavoriteIdsResource::class)]
    #[Endpoint(title: 'getEventUserLikedIds', description: 'Получить пользователей которые лайкали событие')]
    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): EventUserLikedOrFavoriteIdsResource
    {
        $favoriteUsers = Event::findOrFail($id)->favoritesUsers;
        return new EventUserLikedOrFavoriteIdsResource($this->getUsersLikedOrFavorite($request, $favoriteUsers));
    }
    #[Authenticated]
    #[Endpoint(title: 'getHistoryContent', description: 'Получить объект истории. Метод доступен только модерам.')]
    public function getHistoryContent(Request $request, $id): \Illuminate\Http\JsonResponse
    {
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
        ->then(function($historyContent) use ($page, $limit) {
            if(request()->get("last") === true)
            {
                return $historyContent->get()->first();
            } else {
                return $historyContent->cursorPaginate($limit, ['*'], 'page' , $page);
            }
        });
        return response()->json(["status"=>"success", "history_content" => $response]);
    }
    private function saveVkFilesImg($event, $files): void
    {
        $type = FileType::where('name', 'image')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ])->file_types()->attach($type[0]->id);
        }
    }
    private function getUsersLikedOrFavorite($request, $users): array
    {
        $UsersIds = [];

        foreach($users as $user){
            $UsersIds[] = $user;
        }
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $paginator = new LengthAwarePaginator($UsersIds, count($UsersIds), $limit);
        $items = $paginator->getCollection();

        return [
            $paginator->setCollection(
                $items->forPage($page, $limit)
            )->appends(request()->except(['page']))
                ->withPath($request->url())
            ];
    }
    private function saveVkFilesVideo($event, $files): void
    {
        $type = FileType::where('name', 'video')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('video_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveVkFilesLink($event, $files): void
    {
        $type = FileType::where('name', 'link')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('link_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveLocalFilesImg($event, $files): void
    {
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


