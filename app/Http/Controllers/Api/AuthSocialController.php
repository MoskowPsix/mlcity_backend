<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialService;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class AuthSocialController extends Controller
{
    public function index($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        if ($provider === 'vkontakte') {
            return Socialite::driver($provider)
                ->stateless()
                ->scopes(['offline', 'groups', 'stats', 'wall'])
                ->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        } elseif ($provider === 'telegram') {
            return Socialite::driver($provider)
                ->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }
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
