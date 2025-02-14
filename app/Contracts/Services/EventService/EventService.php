<?php

namespace App\Contracts\Services\EventService;

use App\Filters\Event\EventSortByCoords;
use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\EventForAuthorReqeust;
use App\Http\Requests\Event\GetEventRequest;
use App\Http\Requests\Event\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\SearchContentForTextRequest;
use App\Models\Event;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Status;
use App\Models\Timezone;
use App\Contracts\Services\FileService\FileService;
use App\Contracts\Services\OrganizationService\OrganizationService;
use App\Filters\Event\EventAuthorEmail;
use App\Filters\Event\EventAuthorName;
use App\Filters\Event\EventByIds;
use App\Filters\Event\EventDate;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventName;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\Event\EventPlaceAddress;
use App\Filters\Event\EventPlaceGeoPositionInArea;
use App\Filters\Event\EventPlaceLocation;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Event\EventTypes;
use App\Filters\Event\EventWithPlaceFull;
use App\Filters\Sight\SightAuthor;
use App\Models\Organization;
use App\Models\Sight;
use App\Models\User;
use Carbon\Carbon;
use Elastic\Elasticsearch\Client;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pipeline\Pipeline;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EventService implements EventServiceInterface
{
    private FileService $fileService;
    private OrganizationService $organizationService;
    private Client $elasticsearch;


    public function __construct()
    {
        if (config('elasticsearch.enabled')) {
            $this->elasticsearch = resolve(Client::class);
        }
        $this->fileService = new FileService();
        $this->organizationService = new OrganizationService();
    }

    public function getById(int $id): Event
    {
        $event = Event::query()->where('id', $id)->with('price', 'types', 'files', 'statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments', 'viewsUsers');
        $response =
            app(Pipeline::class)
            ->send($event)
            ->through([
                EventWithPlaceFull::class
            ])
            ->via("apply")
            ->then(function ($event) {
                return $event->firstOrFail();
            });

        return $response;
    }

    public function get($data)
    {
        $page = $data->page;
        $limit = $data->limit && ($data->limit < 50) ? $data->limit : 6;
        $events = Event::with('files', 'author', 'types', 'price', 'statuses','viewCount', 'likes', 'organization.sight');

        $response = app(Pipeline::class)
            ->send($events)
            ->through([
//                EventSortByCoords::class,
                // EventTotal::class,
                EventOrderByDateCreate::class,
                EventName::class,
                EventByIds::class,
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
                EventLikedUserExists::class,
            ])
            ->via('apply')
            ->then(function ($events) use ($page, $limit, $data) {
                return $events->withCount('favoritesUsers')->simplePaginate($limit, ['*'], 'page',  $page);
            });
        return $response;

    }


    public function getSearchText(SearchContentForTextRequest $request): object
    {
        $query = [
            'bool' => [
                'must' => [],
                'filter' => []
            ]
        ];

        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 6;

        $events = Event::with('files', 'author', "types", 'price', 'statuses')
            ->withCount('likedUsers', 'favoritesUsers', 'comments');

        if (config('elasticsearch.enabled')) {
            $dateStart = Carbon::now();
            $dateEnd = $dateStart->addYear(3);

            if ($dateStart || $dateEnd) {
                $range = [];

                if ($dateStart) {
                    $range['gte'] = $dateStart;
                }

                if ($dateEnd) {
                    $range['lte'] = $dateEnd;
                }

//                $query['bool']['filter'][] = [
//                    'range' => [
//                        'date_end' => $range,
//                    ]
//                ];
            }

            if ($request->text) {
                $query['bool']['must'][] = [
                    'query_string' => [
                        'fields' => $request->columns,
                        'query' => $request->text . '*',
                        'default_operator' => 'and',
                    ]
                ];
            }

            $model = new Event();
            $searchParams = [
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'body' => [
                    'query' => $query,
                    '_source' => ['id']
                ],
            ];

            $items = $this->elasticsearch->search($searchParams)->asArray();

            $events_ids = [];
            foreach ($items['hits']['hits'] as $item) {
                $events_ids[] = $item['_source']['id'];
            }

            $events = $events->whereIn('id', $events_ids);
        } else {
            foreach ($request->columns as $column) {
                $events = $events->orWhere($column, 'ilike', "%$request->text%");
            }
        }

        return $events->cursorPaginate($limit, ['*'], 'page', $page);
    }


    public function getUserEvents(EventForAuthorReqeust $data)
    {
        isset($data->page) ?  $page = $data->page :  $page = '';
        isset($data->limit) ?  $limit = $data->limit : $limit =  6;
        $events = Event::where('user_id', auth('api')->user()->id)->with('files', 'author', 'price', 'statuses', 'types')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');

        return app(Pipeline::class)
            ->send($events)
            ->through([
                EventStatuses::class,
                EventStatusesLast::class,
            ])
            ->via('apply')
            ->then(function ($events) use ($page, $limit, $data) {
                return $events->orderBy('created_at', 'desc')->cursorPaginate($limit, ['*'], 'page', $page);
            });
    }

    public function store(EventCreateRequest $data): Event
    {
        DB::beginTransaction();
        $user = auth('api')->user();
        try {

            if (!$this->checkUserHaveOrganization()) {
                $coords = explode(',',$data->places[0]['coords']);
                $latitude   = $coords[0]; // широта
                $longitude  = $coords[1];

                $sight = Sight::create([
                    "name" => $user->name,
                    "address" => $data->places[0]['address'],
                    "description" => "",
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location_id' => $data->places[0]['locationId'],
                    "vk_group_id" => $data->vkGroupId,
                    "vk_post_id"  => $data->vkPostId,
                    "user_id" => $user->id,
                ]);
                $types = explode(",",$data->type[0]);
                $sight->types()->sync($types);

                foreach ($data->files as $file) {
                    $filename = uniqid('img_');
                    $path = $file->store('sights/'.$sight->id, 'public');
                    $type = FileType::where('name', 'image')->get();

                    $sight->files()->create([
                        'name'  => $filename,
                        'link'  => '/storage/'.$path,
                        'local' => 1
                    ])->file_types()->sync($type[0]->id);

                    if ($data->localFilesImg) {
                        $this->fileService->saveLocalFilesImg($sight, $data->localFilesImg);
                    }
                    if ($data->vkFilesImg) {
                        $this->fileService->saveVkFilesImg($sight, $data->vkFilesImg);
                    }
                    if($data->localFilesImg || $data->vkFilesImg){
                        $data->$file($sight,  $data->localFilesImg || $data->vkFilesImg);
                    }
                }

                $sight->organization()->create();
            }

            if (!isset($data->organization_id)) {
                $sight = Sight::where('user_id', $user->id)->get()->first();
                $organizationId = Organization::where("sight_id", $sight->id)->get()->first()->id;
            } else {
                $organizationId = $data->organization_id;
            }

            if (!$this->isUserOrganization($user->id, $organizationId)) {
                throw new Exception("Is not user organization");
            }

            $event = Event::create([
                'name'          => $data->name,
                'sponsor'       => $data->sponsor,
                'description'   => $data->description,
                'materials'     => $data->materials,
                'date_start'    => $data->dateStart,
                'date_end'      => $data->dateEnd,
                'user_id'       => $user->id,
                'vk_group_id'   => $data->vkGroupId,
                'vk_post_id'    => $data->vkPostId,
                'age_limit'     => $data->age_limit,
                'organization_id' => $organizationId
            ]);
            // Устанавливаем цену
            foreach ($data->prices as $price) {
                if ($price["cost_rub"] == "") {
                    $event->price()->create([
                        'cost_rub' => 0,
                        'descriptions' => $price['descriptions']
                    ]);
                } else {
                    $event->price()->create([
                        'cost_rub' => $price['cost_rub'],
                        'descriptions' => $price['descriptions']
                    ]);
                }
            }
            // Устанавливаем марки
            foreach ($data->places as $place) {
                $coords = explode(',', $place['coords']);
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

                foreach ($place['seances'] as $seance) {
                    $place_cr->seances()->create([
                        'date_start' => $seance['dateStart'],
                        'date_end' => $seance['dateEnd']
                    ]);
                }
            }
            $types = explode(",", $data->type);
            $event->types()->sync($types);
            if (auth('api')->user()->hasRole('root') || auth('api')->user()->hasRole('Admin')) {
                $status = Status::where('name', 'Опубликовано')->first();
                $event->statuses()->attach($status->id, ['last' => true]);
                $event->likes()->create();
            } else {
                $event->statuses()->attach($data->status, ['last' => true]);
                $event->likes()->create();
            }


            if ($data->vkFilesImg) {
                $this->fileService->saveVkFilesImg($event, $data->vkFilesImg);
            }

            if ($data->vkFilesVideo) {
                $this->fileService->saveVkFilesVideo($event, $data->vkFilesVideo);
            }
            if ($data->vkFilesLink) {
                $this->fileService->saveVkFilesLink($event, $data->vkFilesLink);
            }

            if ($data->localFilesImg) {
                $this->fileService->saveLocalFilesImg($event, $data->localFilesImg);
            }

            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    public function checkUserHaveOrganization(): bool
    {
        $user = auth('api')->user();
        if ($user) {
            $sight = Sight::where("user_id", $user->id)->get();
        } else {
            return false;
        }

        if (count($sight) == 0) {
            return false;
        }

        return true;
    }

    public function isUserOrganization(int $userId, $organizationId): bool
    {
        $res = Sight::where("user_id", $userId)->whereHas("organization", function ($query) use ($organizationId) {
            $query->where("organizations.id", $organizationId);
        })->exists();

        return $res;
    }

    public function setEvenUserLiked(SetEventUserLikedRequest $request): bool
    {
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id', auth('api')->user()->id)->exists()) {
            $event->likedUsers()->sync(auth('api')->user()->id);
            $likedUser = true;
        }
        return $likedUser;
    }

    public function checkLiked(int $id): bool
    {
        $event =  Event::where('id', $id)->firstOrFail();
        return $event->likedUsers()->where('user_id', auth('api')->user()->id)->exists();
    }

    public function checkFavorite(int $id): bool
    {
        $event =  Event::where('id', $id)->firstOrFail();
        return $event->favoritesUsers()->where('user_id', auth('api')->user()->id)->exists();
    }

    public function showForMap(int $id): Event
    {
        return Event::where('id', $id)->with('files', 'author', 'price')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments')->firstOrFail();
    }

    public function getEventUserLiked(int $id, PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 10;
        return Event::findOrFail($id)->likedUsers()->orderBy('id', 'desc')->cursorPaginate($limit, ['*'], 'page', $page);
//        $likedUsersIds = [];
//
//        foreach ($likedUsers as $user) {
//            $likedUsersIds[] = $user;
//        }
//        $page = $request->page;
//        $limit = $request->limit ? $request->limit : 6;
//
//        $paginator = new LengthAwarePaginator($likedUsersIds, count($likedUsersIds), $limit);
//        $items = $paginator->getCollection();
//
//        return  $paginator->setCollection(
//            $items->forPage($page, $limit)
//        )->appends(request()->except(['page']))
//            ->withPath($request->url());
    }

    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 10;
        return Event::findOrFail($id)->favoritesUsers()->orderBy('id', 'desc')->cursorPaginate($limit, ['*'], 'page', $page);
    }

    public function getOrganizationOfEvent($id): Sight
    {
        $event = Event::findOrFail($id);
        $organization = $event->organization->sight;
        $organization->load('files', 'types');

        return $organization;
    }
    public function delete(int $Id): bool
    {
        try {
            $event = Event::where('id', $Id);
            if ($event->firstOrFail()->user_id == auth('api')->user()->id) {
                $event->delete();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function addStatus(int $Id): void
    {
        $user = auth('api')->user() ?? auth('moonshine')->user();
        $statusId = request()->get("status_id");
        if ($this->isUserEvent($Id, $user->id)) {
            $event = Event::find($Id);
            $this->resetExistedStatusesToLast($event);
            $event->statuses()->attach($statusId, ["last" => True]);
        } else {
            abort(403, "You dont have a permission for this action");
        }
    }

    private function resetExistedStatusesToLast($event): void
    {
        $statuses = $event->statuses;
        foreach ($statuses as $status) {
            $event->statuses()->updateExistingPivot($status["id"], [
                "last" => false
            ]);
        }
    }

    public static function isUserEvent($eventId, $userId)
    {
        $res = Event::where("id", $eventId)->where("user_id", $userId)->get();
        return count($res) > 0;
    }

    public function sightTransferOrganization(int $sight_id, int $event_id): void
    {
        $sight = Sight::findOrFail($sight_id);
        $event = Event::findOrFail($event_id);

        $event->update([
             'user_id' => $sight->user_id,
            'organization_id' => $sight->organization()->first()->id,

        ]);
    }

    public function addView(int $id): bool
    {
        try {
            $event = Event::findOrFail($id);
            if (!empty(auth('api')->user())) {
                if ($event->viewsUsers()->where('user_id', auth('api')->user()->id)->exists()) {
                    return false;
                }
                $event->viewsUsers()->create([
                    'user_id' => auth('api')->user()->id,
                    'time_view' => 1,
                ]);
            }
            if ($event->viewCount()->exists()) {
                $event->viewCount()->increment('count');
            } else {
                $event->viewCount()->create(['count' => 1]);
            }

            return true;
        } catch (Exception $e) {
            dd($e);
            return false;
        }
    }
}
