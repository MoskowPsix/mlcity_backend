<?php

namespace App\Contracts\Services\EventService;

use App\Http\Requests\Event\EventCreateRequest;
use App\Http\Requests\Event\SetEventUserLikedRequest;
use App\Http\Requests\PageANDLimitRequest;
use App\Models\Event;
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
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pipeline\Pipeline;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EventService implements EventServiceInterface
{
    public function __construct(
        private readonly FileService $fileService,
        private readonly OrganizationService $organizationService
        )
    {

    }

    public function getById(int $id): Event
    {
        $event = Event::query()->where('id', $id)->with('price', 'types', 'files','statuses', 'author', 'comments')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
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

        return $response;
    }

    public function get($data)
    {
        $total = 0;
        $page = $data->page;
        $limit = $data->limit && ($data->limit < 50)? $data->limit : 6;
        $events = Event::query()->with('files', 'author', "types", 'price', 'statuses',)->withCount('likedUsers', 'favoritesUsers', 'comments');

        return app(Pipeline::class)
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
        ->then(function ($events) use ($page, $limit, $data){
            return $events->cursorPaginate($limit, ['*'], 'page' , $page);
        });
    }

    public function getUserEvents($data)
    {
        isset($data->page) ?  $page = $data->page :  $page = 1;
        isset($data->limit) ?  $limit = $data->limit : $limit =  6;
        $events = Event::where('user_id', auth('api')->user()->id)->with('files', 'author', 'price', 'statuses', 'types')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        $response = $events->orderBy('date_start','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
        return $response;
    }

    public function store(EventCreateRequest $data): Event
    {
        DB::beginTransaction();
        $user = auth('api')->user();
        try {
            if (!$this->checkUserHaveOrganization()) {
                $sight = Sight::create([
                    "name" => $user->name,
                    "address" => "",
                    "description" => "",
                    "user_id" => $user->id,
                ]);
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
//                'sponsor'       => $data->sponsor,
                'description'   => $data->description,
                'materials'     => $data->materials,
                'date_start'    => $data->dateStart,
                'date_end'      => $data->dateEnd,
                'user_id'       => $user->id,
                'vk_group_id'   => $data->vkGroupId,
                'vk_post_id'    => $data->vkPostId,
                'age_limit'     => $data->ageLimit,
                'organization_id' => $organizationId
            ]);
            // Устанавливаем цену
            foreach ($data->prices as $price){
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
            foreach ($data->places as $place){
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
                   $place_cr->seances()->create([
                        'date_start' => $seance['dateStart'],
                        'date_end' => $seance['dateEnd']
                    ]);

                }
            }
            $status = Status::where('name', 'Опубликовано')->first();
            $types = explode(",",$data->type[0]);
            $event->types()->sync($types);
            $event->statuses()->attach($status->id, ['last' => true]);
            $event->likes()->create();


            if ($data->vkFilesImg){
                $this->fileService->saveVkFilesImg($event, $data->vkFilesImg);
            }

            if ($data->vkFilesVideo){
                $this->fileService->saveVkFilesVideo($event, $data->vkFilesVideo);
            }
            if ($data->vkFilesLink){
                $this->fileService->saveVkFilesLink($event, $data->vkFilesLink);
            }

            if ($data->localFilesImg){
                $this->fileService->saveLocalFilesImg($event, $data->localFilesImg);
            }

            DB::commit();

            return $event;

    } catch(Exception $e) {
        DB::rollBack();
        Log::error($e);
        throw $e;
    }
    }

    public function checkUserHaveOrganization(): bool {
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

    public function isUserOrganization(int $userId, $organizationId): bool {
        $res = Sight::where("user_id", $userId)->whereHas("organization", function ($query) use($organizationId) {
            $query->where("organizations.id", $organizationId);
        })->exists();

        return $res;
    }

    public function setEvenUserLiked(SetEventUserLikedRequest $request): bool
    {
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id',auth('api')->user()->id)->exists()){
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
        $likedUsers = Event::findOrFail($id)->likedUsers;
        $likedUsersIds = [];

        foreach ($likedUsers as $user){
            $likedUsersIds[] = $user;
        }
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $paginator = new LengthAwarePaginator($likedUsersIds, count($likedUsersIds), $limit);
        $items = $paginator->getCollection();

        return  $paginator->setCollection(
                $items->forPage($page, $limit)
            )->appends(request()->except(['page']))
                ->withPath($request->url());
    }

    public function getEventUserFavoritesIds($id, PageANDLimitRequest $request): object
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

        return $paginator->setCollection(
                $items->forPage($page, $limit)
            )->appends(request()->except(['page']))
                ->withPath($request->url());
    }

    public function getOrganizationOfEvent($id): Sight
    {
        $event = Event::findOrFail($id);
        $organization = $event->organization->sight;
        $organization->load('files');

        return $organization;
    }

    public function addStatus($eventId) {
        $user = auth('api')->user();
        $statusId = request()->get("status_id");
        if (EventService::isUserEvent($eventId, $user->id)) {
            $event = Event::find($eventId);
            $this->resetExistedStatusesToLast($event);
            $event->statuses()->attach($statusId, ["last" => True]);
        } else {
            abort(403, "You dont have a permission for this action");
        }
    }

    private function resetExistedStatusesToLast($event) {
        $statuses = $event->statuses;
        foreach($statuses as $status) {
            $event->statuses()->updateExistingPivot($status["id"], [
                "last" => false
            ]);
        }
    }

    public static function isUserEvent($eventId, $userId) {
        $res = Event::where("id", $eventId)->where("user_id", $userId)->get();
        return count($res) > 0;
    }
}
