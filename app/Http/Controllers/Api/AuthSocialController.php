<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialService;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;


class AuthSocialController extends Controller
{
    public function index($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)
            ->stateless()
            ->scopes(['offline', 'groups', 'stats', 'wall'])
            ->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
    }

    public function callbackApple() {
        $provider = 'apple';
        if (env('APP_ENV') === 'local') {
            $socialUser = Socialite::driver($provider)->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }

        if (env('APP_ENV') === 'production') {
            $socialUser = Socialite::driver($provider)->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }

        $socialService = new SocialService();
        $user = $socialService->findOrCreateUser($socialUser, $provider);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

    public function callback($provider): \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        //->setHttpClient(new \GuzzleHttp\Client(['verify' => false])) на проде убрать, так как будет сертификат
        if (env('APP_ENV') === 'local') {
            $socialUser = Socialite::driver($provider)->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }

        if (env('APP_ENV') === 'production') {
            $socialUser = Socialite::driver($provider)->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }

        $socialService = new SocialService();
        $user = $socialService->findOrCreateUser($socialUser, $provider);
        $token = $user->createToken('auth_token')->plainTextToken;

        if ($user) {
            return redirect(env('FRONT_APP_URL' ).'/login/'.$token);
        }

        return redirect(env('FRONT_APP_URL' ).'/login');
    }
}
