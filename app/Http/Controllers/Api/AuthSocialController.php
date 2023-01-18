<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialService;
use Laravel\Socialite\Facades\Socialite;


class AuthSocialController extends Controller
{
    public function index($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
    }

    public function callback($provider): \Illuminate\Http\JsonResponse
    {
        $socialUser = Socialite::driver($provider)->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/

        $socialService = new SocialService();
        $user = $socialService->findOrCreateUser($socialUser, $provider);

        if (!$user->isEmpty()){
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'        => 'success',
                'message'       => __('messages.login.success'),
                'access_token'  => $token,
                'token_type'    => 'Bearer',
                'user'          => $user
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('messages.login.errorSocialAuth')
        ], 401);

    }
}
