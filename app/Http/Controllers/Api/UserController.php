<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\Event;
use App\Models\Sight;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Pipeline\Pipeline;
use App\Filters\Users\UsersEmail;
use App\Filters\Users\UsersName;
use App\Filters\Users\UsersId;
use App\Filters\Users\UsersCreated;
use App\Filters\Users\UsersUpdated;
use App\Filters\Users\UsersLocation;
use App\Filters\Users\UsersRegion;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventFavoritesUserExists;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function getUser(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id',auth('api')->user()->id)->with('roles', 'socialAccount')->first();

        return response()->json([
            'status'        => 'success',
            // 'message'       => __('messages.login.success'),
            'user'          => $user,
            'user_id'       => auth('api')->user()->id
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
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 6;
        $request->merge(['userId' => $id]);

        $favoriteEvents = User::findOrFail($id)->favoriteEvents();

        $response =
            app(Pipeline::class)
            ->send($favoriteEvents)
            ->through([
                EventLikedUserExists::class,
                EventFavoritesUserExists::class
            ])
            ->via('apply')
            ->then(function ($favoriteEvents) use ($page, $limit){
                return $favoriteEvents->orderBy('date_start','desc')->paginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'));
            });
        return response()->json([
           'status' =>  'success',
           'result' => $response,
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
            $event['liked_users_exists'] = true;
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
    $page = $request->page;
    $limit = $request->limit ? $request->limit : 6;
    $request->merge(['userId' => $id]);

    $favoriteSights = User::findOrFail($id)->favoriteSights();

    $response =
        app(Pipeline::class)
        ->send($favoriteSights)
        ->through([
            EventLikedUserExists::class,
            EventFavoritesUserExists::class
        ])
        ->via('apply')
        ->then(function ($favoriteSights) use ($page, $limit){
            return $favoriteSights->orderBy('created_at','desc')->paginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'));
        });
    return response()->json([
       'status' =>  'success',
       'result' => $response,
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

    public function chekUserName($name) {
        if (strlen($name) >= 3) {
            $user = User::where('name', $name)->first();
            if ($user) {
                return response()->json([
                    'status' =>  'success',
                    'user_name' =>  false], 200);
            } elseif (!$user) {
                return response()->json([
                    'status' =>  'success',
                    'user_name' =>  true], 200);
            }
        } else {
            return response()->json([
                'status' =>  'error',
                'message' =>  'minimal 3'], 403);
        }
    }

    public function chekUserEmail($email)
    {
        if (strlen($email) >= 3) {
            $user = User::where('email', $email)->first();
            if ($user==null) {
                return response()->json([
                    'status' =>  'success',
                    'user_email' =>  true,"user"=>$user], 200);
            } else {
                return response()->json([
                    'status' =>  'success',
                    'user_email' =>  false,"user"=>$user], 200);
            }
        } else {
            return response()->json([
                'status' =>  'error',
                'message' =>  'min 3 lenght'], 403);
        }
    }

    public function checkUserNumber($number)
    {
        if (strlen($number) >= 3) {
            $user = User::where('number', $number)->first();
            if ($user!==null) {
                return response()->json([
                    'status' =>  'success',
                    'user_number' =>  false], 200);
            } else {
                return response()->json([
                    'status' =>  'success',
                    'user_number' =>  true], 200);
            }
        } else {
            return response()->json([
                'status' =>  'error',
                'message' =>  'min 3 lenght'], 403);
        }
    }
   public function getUserLikedSightsIds($id, Request $request): \Illuminate\Http\JsonResponse
   {
        $likedSights = User::findOrFail($id)->likedSights;

        $likedSightsIds = [];

        foreach ($likedSights as $sight){
            $sight['liked_users_exists'] = true;

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
        User::findOrFail(Auth::user()->id)->likedEvents()->toggle($request->event_id); // верно
        $event =  Event::find($request->event_id); // верно

        if (User::findOrFail(Auth::user()->id)->likedEvents()->where('event_id',$request->event_id)->exists()){
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
        Auth::user()->favoriteSights()->toggle($request->sight_id);

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
        $limit = $request->limit ? $request->limit : 1;
        $name = $request->name ? $request->name : '';
        $users = User::with('roles', 'locations');

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
                UsersLocation::class,
            ])
            ->then(function ($users) use ($page, $limit, $request){
                return $users->orderBy('created_at','desc')->cursorPaginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'));
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
        $user = User::findOrFail($id);
        $user->fill($data);
        $user->save();

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
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

    public function updateUser(UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
          // return response()->json(['hi'], 200)
        if ($data["new_name"])
        {
            if($request->hasFile('avatar')){
                if($request->file('avatar')->isValid()){
                    $file = $request->file('avatar');

                    $path = $file->store('users/'.auth('api')->user()->id.'/avatars','public');
                    $user = User::where('id',auth('api')->user()->id)->first();
                    $user->update(['avatar'=>'/storage/'.$path]);
                }
            }
            $user = User::where('id',auth('api')->user()->id)->first();
            $user->update(['name'=>$data["new_name"]]);

            return response()->json([
                'status'=>'success',
                'message'=>'the user name has been changed',
                'user' => $user
            ], 200);
        }

        else
        {
            return response()->json([
                'status'=>'error',
                'message'=>'maybe your input field is empty'
            ], 401);
        }
    }
}
