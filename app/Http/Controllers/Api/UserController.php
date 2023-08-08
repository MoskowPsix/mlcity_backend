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
    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"User"},
     *     summary="Get user by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     * 
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    
    // Получить юзера по ИД
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
    /**
     * @OA\Get(
     *     path="/users/{id}/social-account",
     *     tags={"User"},
     *     summary="Get social account user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    //Получить социальный аккаунт по ид юзера
    public function getSocialAccountByUserId($user_id){
        return SocialAccount::where('user_id', $user_id)->firstOrFail();
    }
    //Получаем избранные ивенты у юзера
    private function getUserFavoriteEvents()
    {
        return User::findOrFail(Auth::user()->id)->favoriteEvents;
    }
    /**
     * @OA\Get(
     *     path="/users/{id}/favorite-events",
     *     tags={"User"},
     *     summary="Get favorites events user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    
    // Получаем ивенты, которые юзер айкнул
    private function getUserLikedEvents()
    {
        return User::findOrFail(Auth::user()->id)->likedEvents;
    }
    /**
     * @OA\Get(
     *     path="/users/{id}/liked-events",
     *     tags={"User"},
     *     summary="Get liked events user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
   /**
     * @OA\Get(
     *     path="/users/{id}/favorite-sights",
     *     tags={"User"},
     *     summary="Get favorites sights user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/users/{id}/liked-sights",
     *     tags={"User"},
     *     summary="Get liked sights user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/users/like-event-toggle",
     *     tags={"User"},
     *     summary="Add event in like",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="event_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/users/like-sight-toggle",
     *     tags={"User"},
     *     summary="Add sight in like",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="sight_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/users/favorite-event-toggle",
     *     tags={"User"},
     *     summary="Add event in favorite",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="event_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    //Добавляем убираем из избранного
    public function toggleFavoriteEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->favoriteEvents()->toggle($request->event_id);

        return response()->json([
            'status'  => 'success',
        ], 200);
    }
    /**
     * @OA\Post(
     *     path="/users/favorite-sight-toggle",
     *     tags={"User"},
     *     summary="Add sight in favorite",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="sight_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function toggleFavoriteSight(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->favoriteSights()->toggle($request->event_id);

        return response()->json([
            'status'  => 'success',
        ], 200);
    }

    //Методы для Админ панели
    /**
     * @OA\Get(
     *     path="/listUsers",
     *     tags={"User"},
     *     summary="Get all users by filters, method for AdminPanel",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="createdDateStart",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="createdDateEnd",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="updatedDateStart",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="updatedDateEnd",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/updateUsers/{id}",
     *     tags={"User"},
     *     summary="Update user by id, method for AdminPanel",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/deleteUsers/{id}",
     *     tags={"User"},
     *     summary="Delete soft user by id, method for AdminPanel",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function deleteUsers($id): \Illuminate\Http\JsonResponse
    {
        User::find($id)->delete();
        return response()->json(['status' => 'success', 'delete user' => $id], 200);
    }
}
