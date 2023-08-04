<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\Event;
use App\Models\Sight;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Pipeline\Pipeline;
use App\Filters\Users\UsersEmail;
use App\Filters\Users\UsersName;
use App\Filters\Users\UsersId;
use App\Filters\Users\UsersCreated;
use App\Filters\Users\UsersUpdated;
use App\Filters\Users\UsersCity;
use App\Filters\Users\UsersRegion;


class UserController extends Controller
{
    // Получить юзера по ИД
    /**
     * @OA\Get(
     *     path="/users/",
     *     @OA\Response(response="200", description="Display a listing of projects.")
     * )
     */
    public function getUser($id): \Illuminate\Http\JsonResponse
    {
        $user = User::with('roles', 'socialAccount')->findOrFail($id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.login.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200);
    }

    //Получить социальный аккаунт по ид юзера
    public function getSocialAccountByUserId($user_id){
        return SocialAccount::where('user_id', $user_id)->firstOrFail();
    }

    //Получаем избранные ивенты у юзера
    private function getUserFavoriteEvents()
    {
        return User::findOrFail(Auth::user()->id)->favoriteEvents;
    }

    //Получаем массив с ид ивентов, которые юзер добавил в избранное
   public function getUserFavoriteEventsIds($id, Request $request): \Illuminate\Http\JsonResponse
   {
        $favoriteEvents = User::findOrFail($id)->favoriteEvents;

        $favoriteEventsIds = [];

        foreach ($favoriteEvents as $event){
           $favoriteEventsIds[] = $event;
        }
        
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6; 

        $paginator = new LengthAwarePaginator($favoriteEventsIds, count($favoriteEventsIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
           'status' =>  'success',
           'result' =>  $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
        ], 200);
   }

    //Добавляем убираем из избранного
    public function toggleFavoriteEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->favoriteEvents()->toggle($request->event_id);

        return response()->json([
            'status'  => 'success',
        ], 200);
    }

    public function toggleFavoriteSight(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->favoriteSights()->toggle($request->event_id);

        return response()->json([
            'status'  => 'success',
        ], 200);
    }
    
    // Получаем ивенты, которые юзер айкнул
    private function getUserLikedEvents()
    {
        return User::findOrFail(Auth::user()->id)->likedEvents;
    }

    //Получаем массив с ид ивентов, которые юзер лайкнул
   public function getUserLikedEventsIds($id, Request $request): \Illuminate\Http\JsonResponse
   {
        $likedEvents = User::findOrFail($id)->likedEvents;
        $likedEventsIds = [];

        foreach ($likedEvents as $event){
            $likedEventsIds[] = $event;
        }
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;

        $paginator = new LengthAwarePaginator($likedEventsIds, count($likedEventsIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
            'status' => 'success',
            'result' => $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
       ], 200);
   }

   public function getUserFavoriteSightsIds($id, Request $request): \Illuminate\Http\JsonResponse
   {
        $favoriteSights = User::findOrFail($id)->favoriteSights;

        $favoriteSightsIds = [];

        foreach ($favoriteSights as $sight){
           $favoriteSightsIds[] = $sight;
        }
        
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6; 

        $paginator = new LengthAwarePaginator($favoriteSightsIds, count($favoriteSightsIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
           'status' =>  'success',
           'result' =>  $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
        ], 200);
   }
   public function getUserLikedSightsIds($id, Request $request): \Illuminate\Http\JsonResponse
   {
        $likedSights = User::findOrFail($id)->likedSights;

        $likedSightsIds = [];

        foreach ($likedSights as $sight){
           $likedSightsIds[] = $sight;
        }
        
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6; 

        $paginator = new LengthAwarePaginator($likedSightsIds, count($likedSightsIds), $limit);
        $items = $paginator->getCollection();

        return response()->json([
           'status' =>  'success',
           'result' =>  $paginator->setCollection(
                                $items->forPage($page, $limit)
                                )->appends(request()->except(['page']))
                                ->withPath($request->url())
        ], 200);
   }

    //Добавляем убираем лайк
    public function toggleLikedEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->likedEvents()->toggle($request->event_id); // верно
        $event =  Event::find($request->event_id); // верно

        if (Auth::user()->likedEvents()->where('event_id',$request->event_id)->exists()){
            $event->likes()->where('event_id',$request->event_id)->exists()
                ? $event->likes->increment('local_count')
                : $event->likes()->create(["local_count" => 1]);
        } else if ($event->likes()->where('event_id',$request->event_id)->exists()){
            if ($event->likes->local_count > 0)
                $event->likes->decrement('local_count');
        }

        return response()->json([
            'status'  => 'success',
        ], 200);
    }

    public function toggleLikedSight(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->likedSights()->toggle($request->sight_id); // верно
        $sight =  Sight::find($request->sight_id); // верно

        if (Auth::user()->likedSights()->where('sight_id',$request->sight_id)->exists()){
            $sight->likes()->where('sight_id',$request->sight_id)->exists()
                ? $sight->likes->increment('local_count')
                : $sight->likes()->create(["local_count" => 1]);
        } else if ($sight->likes()->where('sight_id',$request->sight_id)->exists()){
            if ($sight->likes->local_count > 0)
                $sight->likes->decrement('local_count');
        }

        return response()->json([
            'status'  => 'success',
        ], 200);
    }

    //Методы для Админ панели
    //Получить всех юзеров через фильтры
    public function listUsers(Request $request) 
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;
        $name = $request->name ? $request->name : '';
        $users = User::with('roles');

        $response =
            app(Pipeline::class)
            ->send($users)
            ->via('apply')
            ->through([
                UsersId::class,
                UsersName::class,
                UsersEmail::class,
                UsersCreated::class,
                UsersUpdated::class,
                UsersCity::class,
                UsersRegion::class
            ])
            ->then(function ($users) use ($page, $limit, $request){
                return $users->orderBy('created_at','desc')->paginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'));
            });

            return response()->json(['status' => 'success', 'users' => $response], 200);
    }  


    public function updateUsers(Request $request, $id)
    {
    
        $data = $request->all();
        $user = User::where('id', $id)->firstOrFail();
        $user->fill($data);
        $user->save();
    
        $jsonData = [
            'status' => 'SUCCESS',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'city' => $user->city,
                'region' => $user->region,
            ]
        ];
    
        return response()->json($jsonData);
    }


    public function deleteUsers($id): \Illuminate\Http\JsonResponse
    {
        User::find($id)->delete();
        return response()->json(['status' => 'success', 'delete user' => $id], 200);
    }
}
