<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventCity;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventRegion;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Sight\SightTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\SightCreateRequest;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class SightController extends Controller
{
    public function getSights(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $sights = Sight::query()->with('types', 'files', 'likes','statuses', 'comments');

        $response =
            app(Pipeline::class)
                ->send($sights)
                ->through([
                    //фильтры такие же как для местоа, если что то поменяется то надо будет разносить
                    EventLikedUserExists::class,
                    EventFavoritesUserExists::class,
                    EventStatuses::class,
                    EventStatusesLast::class,
                    EventCity::class,
                    EventRegion::class,
                    SightTypes::class,
                    EventGeoPositionInArea::class,
                    EventSearchText::class
                ])
                ->via('apply')
                ->then(function ($sights) use ($pagination , $page, $limit){
                    return $pagination === 'true'
                        ? $sights->orderBy('created_at','desc')->paginate($limit, ['*'], 'page' , $page)
                        : $sights->orderBy('created_at','desc')->get();
                });

        return response()->json(['status' => 'success', 'sights' => $response], 200);
    }

    public function updateVkLikes(Request $request){
        $sight = Sight::find($request->sight_id);
        $sight->likes()->update(['vk_count' => $request->likes_count]);
    }

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

    //Проверяем лайкал ли авторизованный юзер этот место
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Sight::where('id', $id)->firstOrFail();

        $liked = $sight->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }

    //Проверяем добавил ли авторизованный юзер этот место в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $sight =  Event::where('id', $id)->firstOrFail();

        $favorite = $sight->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $sight = Sight::where('id', $id)->with('types', 'files', 'likes','statuses', 'comments')->firstOrFail();

        return response()->json($sight, 200);
    }

    public function create(SightCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $coords = explode(',',$request->coords);
        $latitude   = $coords[0]; // широта
        $longitude  = $coords[1]; // долгота

        $sight = Sight::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            'city'          => $request->city,
            'address'       => $request->address,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'description'   => $request->description,
            'price'         => $request->price,
            'materials'     => $request->materials,
            'user_id'       => Auth::user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
        ]);

        $sight->types()->sync($request->type);
        $sight->statuses()->attach($request->status, ['last' => true]);
        $sight->likes()->create();
//        $sight->likes()->create([
//            "vk_count" => $request->vkLikesCount ? $request->vkLikesCount : 0,
//        ]);


        if ($request->vkFiles){
            $this->saveVkFiles($sight, $request->vkFiles);
        }

        if ($request->localFiles){
            $this->saveLocalFiles($sight, $request->localFiles);
        }

        return response()->json(['status' => 'success',], 200);
    }

    public function update($id): \Illuminate\Http\JsonResponse
    {
        dd('update');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        dd('delete');
    }

    private function saveVkFiles($sight, $files){
        foreach ($files as $file) {
            $sight->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ]);
        }
    }

    private function saveLocalFiles($sight, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_');

            $path = $file->store('sights/'.$sight->id, 'public');

            $sight->files()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ]);

        }

    }
}
