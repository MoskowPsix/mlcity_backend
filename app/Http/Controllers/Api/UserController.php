<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomResourceCollection;
use App\Http\Resources\Organization\getUserOrganizations\GetUserOrganizationsOrganizationSuccessResource;
use App\Http\Resources\Organization\OrganizationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\Event;
use App\Models\Sight;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Pipeline\Pipeline;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\Users\UsersEmail;
use App\Filters\Users\UsersName;
use App\Filters\Users\UsersId;
use App\Filters\Users\UsersCreated;
use App\Filters\Users\UsersUpdated;
use App\Filters\Users\UsersLocation;
use App\Filters\Users\UsersRegion;
use App\Filters\Event\EventLikedUserExists;
use App\Filters\Event\EventFavoritesUserExists;
use App\Filters\Organization\OrganizationId;
use App\Filters\Organization\OrganizationName;
use App\Http\Requests\Organisation\CreateOrganisation;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Organization;
use App\Models\UserAgreement;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{

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
        Auth::user()->favoriteSights()->toggle($request->sight_id);

        return response()->json([
            'status'  => 'success',
        ], 200);
    }

    //Методы для Админ панели

    //Получить всех юзеров через фильтры
    public function listUsers(Request $request)
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 10;
        $name = $request->name ? $request->name : '';
        $users = User::with('roles', 'locations');

        $response =
            app(Pipeline::class)
            ->send($users)
            ->via('apply')
            ->through([
                EventOrderByDateCreate::class,
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

    public function deleteUsers($id): \Illuminate\Http\JsonResponse
    {
        User::find($id)->delete();
        return response()->json(['status' => 'success', 'delete user' => $id], 200);
    }

    public function deleteForUsers(): \Illuminate\Http\JsonResponse
    {
        User::find(auth('api')->user()->id)->delete();
        return response()->json(['status' => 'success', 'delete_user' => auth('api')->user()->id], 200);
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

    public function addOrganization($usr_id, CreateOrganisation $request){
        $data = $request->validated();
        $user_id = auth("api")->user()->id;

        if($user_id == $usr_id){
            $data["user_id"] = $user_id;
            $organization = Organization::create($data);
        }
        else{
            return response()->json(["message"=>"Access denied"], 403);
        }


        return response()->json(["message" => "created", "data"=>["organization"=>$organization]], 201);
    }

    public function getOrganizations(Request $request): mixed
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 5;
        $user_id = auth('api')->user()->id;
        $organizations = Organization::where("user_id", $user_id);
        $response =
        app(Pipeline::class)
            ->send($organizations)
            ->through([
                OrganizationName::class,
            ])
            ->via("apply")
            ->then(function ($organizations) use($limit, $page) {
                return $organizations->orderBy('updated_at', 'desc')->cursorPaginate($limit, ['*'], 'page' , $page);
//                return $organizations->orderBy('updated_at', 'desc')->paginate(10);

            });
        return response()->json(new GetUserOrganizationsOrganizationSuccessResource($response));
    }

    public function acceptAgreement(Request $reqeust){
        try{
            $user = auth('api')->user();

            $agreement =  UserAgreement::find($reqeust->get("agreement_id"))->id;
            info($agreement);
            $checkUserAgreementIsAlredy = $user->userAgreements()->where("user_agreement_id",$agreement)->get();
            info($checkUserAgreementIsAlredy);
            if(count($checkUserAgreementIsAlredy) > 0){
                return response()->json(["message"=>"User is alredy accepted this agreement"]);
            }
            if (isset($agreement)){
                $user->userAgreements()->attach([$agreement => ["status" => true]]);

                return response()->json(["message"=>"success", "data" => "agreement accepted"]);
            }
            else{
                return response()->json(["message"=>"agreement not found"]);
            }
        }
        catch (Exception $e) {
            info($e);
            return response()->json(["message"=>"server error"], 500);
        }
    }

    public function checkAgreement(Request $reqeust, $agreement_id){
        // На фронте возникают баги, по этому пока нужно убрать
        // $user = auth("api")->user();

        // $agreement = $user->userAgreements()->where("user_agreement_id",$agreement_id)->first();
        // info($agreement);
        // if($agreement){
        //     return response()->json(["message"=>"success", "data"=>$agreement]);
        // }
        return response()->json(["message" => "true"],200);
    }
}
