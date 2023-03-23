<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class AuthSocialController extends Controller
{
    public function index($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider)->stateless()->scopes(['offline', 'groups', 'stats'])->redirect(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
    }

    public function callback($provider): \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $socialUser = Socialite::driver($provider)->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->stateless()->user(); // $driver - какая соц. сеть. подробнее https://socialiteproviders.com/
        //->setHttpClient(new \GuzzleHttp\Client(['verify' => false])) на проде убрать, так как будет сертификат
        $socialService = new SocialService();
        $user = $socialService->findOrCreateUser($socialUser, $provider);

        if ($user) {
            return redirect('http://localhost:8100/login/'.$user->id);
            //return redirect('http://80.90.190.252:3000/login/'.$user->id);
//            return redirect('http://localhost:4200/login/'.$user->id);
        }
//            $token = $user->createToken('auth_token')->plainTextToken;
//            $user->createToken('auth_token')->plainTextToken;
//            Auth::login($user);
//
//
//            return response()->json([
//                'status'            => 'success',
//                'message'           => __('messages.login.success'),
//                'access_token'      => $token,
//                'token_type'        => 'Bearer',
//                'user'              => $user->load('socialAccount')
//            ], 200);
//        }
//
//        return response()->json([
//            'status' => 'error',
//            'message' => __('messages.login.errorSocialAuth')
//        ], 401);

        //return redirect('http://localhost:4200/login');
        //return redirect('http://80.90.190.252:3000/login/'.$user->id);
        return redirect('http://localhost:8100/login');
    }
}
