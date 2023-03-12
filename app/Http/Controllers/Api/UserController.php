<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
