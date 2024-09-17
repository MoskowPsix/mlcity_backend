<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialService;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class AuthSocialController extends Controller
{
    public function index($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        if ($provider == 'apple') {
            return Socialite::driver($provider)
            ->stateless()
            ->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        } else {
            return Socialite::driver($provider)
            ->stateless()
            ->scopes(['offline', 'groups', 'stats', 'wall'])
            ->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        }
    }

    public function callbackApple() {
        $body = request();
        if (!empty(SocialAccount::where('provider_id', $body['user'])->first())) {
            $user = SocialAccount::where('provider_id', $body['user'])->firstOrFail()->user()->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'token' => $token
            ], 200);
        } elseif (isset($body['givenName']) && isset($body['user']) && $body['authorizationCode']) {
            $email = $body['email'] ? $body['email'] : '';
            $name = $body['givenName'] ? $body['givenName'] : 'user_' . Str::random(8);

            if (!isset($body['givenName'])) {
                while(!User::where('name' ,$name)->count() == 0) {
                    $name = 'user_' . Str::random(8);
                }
            }
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'avatar' => 'https://api.dicebear.com/7.x/pixel-art/svg?seed='. bcrypt($name),
                'password' => bcrypt(Str::random(8)),
            ]);
            $social = $user->socialAccount()->create([
                'provider_id' => $body['user'],
                'provider' => 'apple',
                'token' =>  $body['authorizationCode'],
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'token' => $token
            ], 200);
        }

        // $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'error',
            'message' => 'not found arguments'
        ], 200);
    }

    public function yandex() {
        return Socialite::driver('yandex')
            ->stateless()
            ->redirect();
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
            return redirect(env('FRONT_APP_URL' ).'/login/'.$token .',token');
        }

        return redirect(env('FRONT_APP_URL' ).'/login');
    }
}
