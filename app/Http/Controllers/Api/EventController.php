<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventCity;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventController extends Controller
{
    public function getEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;

        $events = Event::query()->with('types', 'files', 'likes','statuses');

        $response =
            app(Pipeline::class)
            ->send($events)
            ->through([
                EventLikedUserExists::class,
                EventFavoritesUserExists::class,
                EventStatuses::class,
                EventStatusesLast::class,
                EventCity::class,
                EventGeoPositionInArea::class
            ])
            ->via('apply')
            ->then(function ($events) use ($pagination , $page){
                return $pagination === 'true'
                    ? $events->orderBy('date_start','desc')->paginate(6, ['*'], 'page' , $page)
                    : $events->orderBy('date_start','desc')->get();
            });

        return response()->json(['status' => 'success', 'events' => $response], 200);
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
        $event = Event::where('id', $id)->with('types', 'files', 'likes','statuses')->firstOrFail();

        return response()->json($event, 200);
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
