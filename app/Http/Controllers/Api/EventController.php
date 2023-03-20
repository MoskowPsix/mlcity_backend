<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use App\Constants\StatusesConstants;

class EventController extends Controller
{
    public function getLastPublish(Request $request): \Illuminate\Http\JsonResponse
    {
        $city = $request->city;
        $page = $request->page;
        $lat_coords = $request->latitude ? $request->latitude : [];
        $lon_coords = $request->longitude ? $request->longitude : [];
        $userId = $request->user_id; // чтобы жадно выкинуть избранное для авторизованного юзера

        $events = Event::with('types', 'files', 'likes')
        ->withExists(['favoritesUsers' => function($q) use ($userId){
                $q->where('user_id', $userId);
        }])
        ->withExists(['likedUsers' => function($q) use ($userId){
            $q->where('user_id', $userId);
        }])
        ->whereHas('statuses', function($q){
            $q->where('name', StatusesConstants::STATUS_PUBLISH)->where('last', true);
        })
        ->where('city',$city)
        ->orWhere(function($q) use ($lat_coords, $lon_coords){
            $q->whereBetween('latitude', [55.843600, 95.843600])
            ->whereBetween('longitude', [55.843600, 95.843600]);
//            $q->whereBetween('latitude', $lat_coords)
//                ->whereBetween('longitude', $lon_coords);
        })
        ->orderBy('date_start','desc')
        ->paginate(10, ['*'], 'page' , $page);

        return response()->json([
            'status' => 'success',
            'events' => $events,
        ], 200);
    }

    //Проверить этот метод
    public function getLastPublishByCoords(Request $request): \Illuminate\Http\JsonResponse
    {
        //$lat_coords и $lon_coords массивы вида [56.843600, 95.843600]
        $lat_coords = $request->latitude;
        $lon_coords = $request->longitude;

        $events = Event::with('types')
            ->whereBetween('latitude', $lat_coords)
            ->whereBetween('longitude', $lon_coords)
            ->whereHas('statuses', function($q){
                $q->where('name', StatusesConstants::STATUS_PUBLISH)->where('last', true);
            })->get();

        return response()->json([
            'status' => 'success',
            'events' => $events
        ], 200);
    }

    //Проверить этот метод
    public function getUserEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $events = Auth::user()->events()->with('types', 'files', 'statuses')->paginate(10, ['*'], 'page' , $request->page);

        return response()->json([
             'status' => 'success',
             'events' => $events
         ], 200);
    }

    public function updateVkLikes(Request $request){
        $event = Event::find($request->event_id);
        $event->likes()->update(['vk_count' => $request->likes_count]);
    }

    //Создаем отношение - юзер лайкнул ивент
    public function setEvenUserLiked(Request $request): \Illuminate\Http\JsonResponse{
        $event = Event::find($request->event_id);
        $likedUser = false;

        if (!$event->likedUsers()->where('user_id',Auth::user()->id)->exists()){
            $event->likedUsers()->sync(Auth::user()->id);
            $likedUser = true;
        }

        return response()->json(['likedUser' => $likedUser], 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        dd('show');
    }

    public function create(EventCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $coords = explode(',',$request->coords);
        $latitude   = $coords[0]; // широта
        $longitude  = $coords[1]; // долгота

        $event = Event::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            'city'          => $request->city,
            'address'       => $request->address,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            'description'   => $request->description,
            'price'         => $request->price,
            'materials'     => $request->materials,
            'date_start'    => $request->dateStart,
            'date_end'      => $request->dateEnd,
            'user_id'       => Auth::user()->id,
            'vk_group_id'   => $request->vkGroupId,
            'vk_post_id'    => $request->vkPostId,
        ]);

        $event->types()->sync($request->type);
        $event->statuses()->attach($request->status, ['last' => true]);
        $event->likes()->create();
//        $event->likes()->create([
//            "vk_count" => $request->vkLikesCount ? $request->vkLikesCount : 0,
//        ]);


        if ($request->vkFiles){
            $this->saveVkFiles($event, $request->vkFiles);
        }

        if ($request->localFiles){
            $this->saveLocalFiles($event, $request->localFiles);
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

    private function saveVkFiles($event, $files){
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ]);
        }
    }

    private function saveLocalFiles($event, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_');

            $path = $file->store('events/'.$event->id, 'public');

            $event->files()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ]);

        }

    }
}
