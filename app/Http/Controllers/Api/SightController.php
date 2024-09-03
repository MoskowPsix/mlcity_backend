<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\SightService\SightService;
use App\Filters\Event\EventRegion;
use App\Filters\HistoryContent\HistoryContentLast;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\Sight\CreateSightRequest;
use App\Http\Requests\Sight\GetSightsForMapRequest;
use App\Http\Requests\Sight\GetSightsRequest;
use App\Http\Resources\Sight\CheckFavorite\SuccessCheckFavoriteSightResource;
use App\Http\Resources\Sight\CheckLiked\SuccessCheckLickedSightResource;
use App\Http\Resources\Sight\CreateSight\SuccessCreateSightResource;
use App\Http\Resources\Sight\GetEventsInSight\SuccessGetEventsInSightsResource;
use App\Http\Resources\Sight\GetSight\SuccessGetSightResource;
use App\Http\Resources\Sight\GetSightForAuthor\SuccessGetSightForAuthorResource;
use App\Http\Resources\Sight\GetSightForMap\SuccessGetSightsForMapResource;
use App\Http\Resources\Sight\Show\SuccessShowSightResource;
use App\Http\Resources\Sight\ShowForCard\SuccessShowForCardResource;
use App\Models\HistoryContent;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pipeline\Pipeline;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Models\Status;

#[Group(name: 'Sights', description: 'Места')]
class SightController extends Controller
{
    public function __construct(private readonly SightService $sightService) {}
    #[ResponseFromApiResource(SuccessGetSightResource::class)]
    #[Endpoint(title: 'getSights', description: 'Возвращает все места по фильтрам')]
    public function getSights(GetSightsRequest $request): SuccessGetSightResource
    {
        $response = $this->sightService->getSights($request);
        return new SuccessGetSightResource($response);
    }
    #[ResponseFromApiResource(SuccessGetSightResource::class)]
    #[Endpoint(title: 'getSightsForMap', description: 'Возвращает все места по фильтрам для карты')]
    public function getSightsForMap(GetSightsForMapRequest $request): SuccessGetSightsForMapResource
    {
        $response = $this->sightService->getSightsForMap($request);
        return new SuccessGetSightsForMapResource($response);
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessGetSightForAuthorResource::class)]
    #[Endpoint(title: 'getSightsForAuthor', description: 'Возвращает все места по фильтрам для автора')]
    public function getSightsForAuthor(PageANDLimitRequest $request): SuccessGetSightForAuthorResource
    {
        $response = $this->sightService->getSightsForAuthor($request);
        return new SuccessGetSightForAuthorResource($response);
    }
    #[ResponseFromApiResource(SuccessShowForCardResource::class)]
    #[Endpoint(title: 'showForCard', description: 'Возвращает место по id для крточки')]
    public function showForCard(int $id): SuccessShowForCardResource
    {
        $response = $this->sightService->showForCard($id);
        return new SuccessShowForCardResource($response);
    }
//    Скорее всего метод уже не используется, нужно проверить
//    public function updateVkLikes(Request $request){
//        $sight = Sight::find($request->sight_id);
//        $sight->likes()->update(['vk_count' => $request->likes_count]);
//    }
//  Не нашёл метода в маршрутах
//    Создаем отношение - юзер лайкнул место
//    public function setSightUserLiked(Request $request): \Illuminate\Http\JsonResponse
//    {
//        $sight = Sight::find($request->sight_id);
//        $likedUser = false;
//        if (!$sight->likedUsers()->where('user_id',Auth::user()->id)->exists()){
//            $sight->likedUsers()->sync(Auth::user()->id);
//            $likedUser = true;
//        }
//        return response()->json(['likedUser' => $likedUser], 200);
//    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessCheckLickedSightResource::class)]
    #[Endpoint(title: 'checkLiked', description: 'Проверяем лайкал ли авторизованный юзер этот место')]
    //Проверяем лайкал ли авторизованный юзер этот место
    public function checkLiked(int $id): SuccessCheckLickedSightResource
    {
        $liked = $this->sightService->checkLiked($id);
        return  new SuccessCheckLickedSightResource($liked);
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessCheckFavoriteSightResource::class)]
    #[Endpoint(title: 'checkFavorite', description: 'Проверяем добавил ли авторизованный юзер этот место в избранное')]
    //Проверяем добавил ли авторизованный юзер этот место в избранное
    public function checkFavorite(int $id): SuccessCheckFavoriteSightResource
    {
        $favorite = $this->sightService->checkFavorite($id);
        return  new SuccessCheckFavoriteSightResource($favorite);
    }
    #[ResponseFromApiResource(SuccessShowSightResource::class)]
    #[Endpoint(title: 'show', description: 'Получение место по id')]
    public function show(int $id): SuccessShowSightResource
    {
        $response = $this->sightService->show($id);
        return new SuccessShowSightResource($response);
    }
    #[ResponseFromApiResource(SuccessGetEventsInSightsResource::class)]
    #[Endpoint(title: 'getEventsInSight', description: 'Получение событий в месте по id')]
    public function getEventsInSight(PageANDLimitRequest $request, int $id): SuccessGetEventsInSightsResource
    {
        $response = $this->sightService->getEventsInSight($request, $id);
        return new SuccessGetEventsInSightsResource($response);

    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessCreateSightResource::class)]
    #[Endpoint(title: 'create', description: 'Создание мест')]
    public function create(CreateSightRequest $request): SuccessCreateSightResource
    {
        $response = $this->sightService->create($request);
        return new SuccessCreateSightResource($response);
    }
    #[ResponseFromApiResource(SuccessGetSightUserLikedAndFavoriteResource::class)]
    #[Endpoint(title: 'getSightUserLikedIds', description: 'Получение пользователей которые лайкнули место')]
    public function getSightUserLikedIds(int $id, PageANDLimitRequest $request): SuccessGetSightUserLikedAndFavoriteResource
    {
        $response = $this->sightService->getSightUserLikedIds($id, $request);
        return new SuccessGetSightUserLikedAndFavoriteResource($response);
    }
    #[ResponseFromApiResource(SuccessGetSightUserLikedAndFavoriteResource::class)]
    #[Endpoint(title: 'getSightUserFavoritesIds', description: 'Получение пользователей которые добавили в избранное место')]
    public function getSightUserFavoritesIds($id, PageANDLimitRequest $request): SuccessGetSightUserLikedAndFavoriteResource
    {
        $response = $this->sightService->getSightUserFavoritesIds($id, $request);
        return new SuccessGetSightUserLikedAndFavoriteResource($response);
    }
    public function getHistoryContent(PageANDLimitRequest $request, $id): JsonResource
    {
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
        ->then(function($historyContent) use ($page, $limit) {

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
