<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\Event;

class UserController extends Controller
{
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
//    public function getUserFavoriteEventsIds(): \Illuminate\Http\JsonResponse
//    {
//        $favoriteEvents = $this->getUserFavoriteEvents();
//
//        $favoriteEventsIds = [];
//
//        foreach ($favoriteEvents as $event){
//            $favoriteEventsIds[] = $event->id;
//        }
//
//        return response()->json([
//            'status'            => 'success',
//            'favoriteEventsIds' => $favoriteEventsIds
//        ], 200);
//    }

    //Добавляем убираем из избранного
    public function toggleFavoriteEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::user()->favoriteEvents()->toggle($request->event_id);

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
//    public function getUserLikedEventsIds(): \Illuminate\Http\JsonResponse
//    {
//        $likedEvents = $this->getUserLikedEvents();
//
//        $likedEventsIds = [];
//
//        foreach ($likedEvents as $event){
//            $likedEventsIds[] = $event->id;
//        }
//
//        return response()->json([
//            'status'            => 'success',
//            'likedEventsIds' => $likedEventsIds
//        ], 200);
//    }

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
}
