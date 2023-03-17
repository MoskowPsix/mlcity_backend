<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SocialAccount;

class UserController extends Controller
{
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

    public function getSocialAccountByUserId($user_id){
        return SocialAccount::where('user_id', $user_id)->firstOrFail();
    }

    private function getUserFavoriteEvents()
    {
        return User::findOrFail(Auth::user()->id)->favoriteEvents;
    }

    public function getUserFavoriteEventsIds(): \Illuminate\Http\JsonResponse
    {
        $favoriteEvents = $this->getUserFavoriteEvents();

        $favoriteEventsIds = [];

        foreach ($favoriteEvents as $event){
            $favoriteEventsIds[] = $event->id;
        }

        return response()->json([
            'status'            => 'success',
            'favoriteEventsIds' => $favoriteEventsIds
        ], 200);
    }

    //Добавляем убираем из избранного
    public function toggleFavoriteEvent(Request $request){
        Auth::user()->favoriteEvents()->toggle($request->event_id);

        return response()->json([
            'status'  => 'success',
            'user_id' => Auth::user()->id
        ], 200);
    }
}
