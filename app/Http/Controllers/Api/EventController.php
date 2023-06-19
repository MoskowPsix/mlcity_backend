<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventName;
use App\Filters\Event\EventAuthorEmail;
use App\Filters\Event\EventAuthorName;
use App\Filters\Event\EventSponsor;
use App\Filters\Event\EventAddress;
use App\Filters\Event\EventCity;
use App\Filters\Event\EventDate;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Event\EventGeoPositionInArea;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventRegion;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventStatuses;
use App\Filters\Event\EventStatusesLast;
use App\Filters\Event\EventTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function getEvents(Request $request): \Illuminate\Http\JsonResponse
    {
        $pagination = $request->pagination;
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $events = Event::query()->with('types', 'files', 'likes','statuses', 'author');

        $response =
            app(Pipeline::class)
            ->send($events)
            ->through([
                EventName::class,
                EventLikedUserExists::class,
                EventFavoritesUserExists::class,
                EventStatuses::class,
                EventStatusesLast::class,
                EventCity::class,
                EventRegion::class,
                EventDate::class,
                EventTypes::class,
                EventGeoPositionInArea::class,
                EventSearchText::class,
                EventAddress::class,
                EventSponsor::class,
                EventAuthorName::class,
                EventAuthorEmail::class,
                
            ])
            ->via('apply')
            ->then(function ($events) use ($pagination , $page, $limit){
                return $pagination === 'true'
                    ? $events->orderBy('date_start','desc')->paginate($limit, ['*'], 'page' , $page)
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

    //Проверяем лайкал ли авторизованный юзер этот ивент
    public function checkLiked($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $liked = $event->likedUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($liked, 200);
    }

    //Проверяем добавил ли авторизованный юзер этот ивент в избранное
    public function checkFavorite($id): \Illuminate\Http\JsonResponse
    {
        $event =  Event::where('id', $id)->firstOrFail();

        $favorite = $event->favoritesUsers()->where('user_id', Auth::user()->id)->exists();

        return  response()->json($favorite, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $event = Event::where('id', $id)->with('types', 'files', 'likes','statuses', 'author')->firstOrFail();

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

    public function updateEvent(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $event = Event::where('id', $id)->firstOrFail();
        $event->fill($data);
        $event->save();
    
        $jsonData = [
            'status' => 'SUCCESS',
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'sponsor' => $event->email,
                'city' => $event->city,
                'address' => $event->address,
                'description' => $event->description,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'price' => $event->price,
                'materials' => $event->materials,
                'date_start' => $event->date_start,
                'date_end' => $event->date_end,
                
            ]
        ];
    
        return response()->json($jsonData);
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
