<?php

namespace App\Contracts\Services\SightService;

use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventName;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Place\PlaceAddress;
use App\Filters\Place\PlaceGeoPositionInArea;
use App\Filters\Sight\SightAuthor;
use App\Filters\Sight\SightByIds;
use App\Filters\Sight\SightEvents;
use App\Filters\Sight\SightIco;
use App\Filters\Sight\SightLocation;
use App\Filters\Sight\SightTypes;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\Sight\CreateSightRequest;
use App\Http\Requests\Sight\GetSightsForMapRequest;
use App\Http\Requests\Sight\GetSightsRequest;
use App\Models\FileType;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class SightService implements SightServiceInterface
{
    /**
     * @throws \Exception
     */
    public function show(int $id): Sight
    {
        $sight = Sight::where('id', $id)->with('types', 'files', 'likes','statuses', 'author', 'comments', 'locations', 'prices', 'organization');
            $response = app(Pipeline::class)
                ->send($sight)
                ->through([
                    SightEvents::class
                ])
                ->via("apply")
                ->then(function ($sight){
                    return $sight->first();
                });
            empty($response) ? throw new \Exception('Sight not found') : null;
            return $response;
    }
    public function getSights(GetSightsRequest $request): object
    {
//        $pagination = $request->pagination;
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
                ->then(function ($sights) use ( $page, $limit){
                    $sights = $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
                    return $sights;
                });

        return $response;
    }
    public function getSightsForMap(GetSightsForMapRequest $request): object
    {
        $sights = Sight::query();
        return app(Pipeline::class)
            ->send($sights)
            ->through([
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
                return $sights->get();
            });
    }
    public function getSightsForAuthor(PageANDLimitRequest $request): object
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        $sights = Sight::where('user_id', auth('api')->user()->id)->with('files', 'author', 'statuses', 'types')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments');
        return $sights->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page);
    }
    public function showForCard(int $id): Sight
    {
        return Sight::where('id', $id)->with('files', 'author')->withCount('viewsUsers', 'likedUsers', 'favoritesUsers', 'comments')->firstOrFail();
    }
    public function checkLiked(int $id): bool
    {
        $sight =  Sight::where('id', $id)->firstOrFail();
        return $sight->likedUsers()->where('user_id', auth('api')->user()->id)->exists();
    }
    public function checkFavorite(int $id): bool
    {
        $sight =  Sight::where('id', $id)->firstOrFail();
        return $sight->favoritesUsers()->where('user_id', auth('api')->user()->id)->exists();
    }
    public function create(CreateSightRequest $request): Sight
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
            'materials'     => $request->materials,
            'user_id'       => auth("api")->user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
            'organization_id' => 1,
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
        $sight->types()->sync($types);
        $sight->statuses()->attach($request->status, ['last' => true]);
        $sight->likes()->create();

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

        return $sight;
    }
    public function getEventsInSight(PageANDLimitRequest $request, int $id): object
    {
        $sight = Sight::findOrFail($id);
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 6;
        return $sight->events()->cursorPaginate($limit, ['*'], 'page' , $page);
    }
    public function getSightUserLikedIds(int $id, PageANDLimitRequest $request): object
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
        return $paginator->setCollection(
            $items->forPage($page, $limit)
        )->appends(request()->except(['page']))
            ->withPath($request->url());
    }
    public function getSightUserFavoritesIds($id, PageANDLimitRequest $request): object
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

        return $paginator->setCollection(
                $items->forPage($page, $limit)
            )->appends(request()->except(['page']))
                ->withPath($request->url());
    }
    private function saveVkFilesImg(Sight $sight, array $files){
        $type = FileType::where('name', 'image')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ])->file_types()->attach($type[0]->id);
        }

    }
    private function saveVkFilesVideo(Sight $sight, array $files){
        $type = FileType::where('name', 'video')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('video_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    private function saveVkFilesLink(Sight $sight, array $files){
        $type = FileType::where('name', 'link')->get();
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('link_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }

    private function saveLocalFilesImg(Sight $sight, array $files){
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
