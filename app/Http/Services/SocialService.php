<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Str;

class SocialService {

    public function findOrCreateUser($socialUser, $provider)
    {
        if ($user = $this->findUserBySocialId($provider, $socialUser->getId())) {
            return $user;
        }

        if ($user = $this->findUserByEmail($socialUser->getEmail())) {
            $this->addSocialAccount($provider, $user, $socialUser);
            return $user;
        }
        // $user = User::create([
        //     'name'      => $socialUser->getName(),
        //     'avatar'    => $socialUser->getAvatar(),
        //     'password'  => bcrypt(Str::random(8)),
        //     'email' => $socialUser->getEmail(),
        //     'email_verified_at' => date("Y-m-d H:i:s", strtotime('now')),
        // ]);
        switch($provider) {
            case "vkontakte":
                $user = User::create([
                    'name'      => $socialUser->getName(),
                    'avatar'    => $socialUser->getAvatar(),
                    'password'  => bcrypt(Str::random(8)),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => date("Y-m-d H:i:s", strtotime('now')),
                ]);
                break;
            case "telegram":
                $user = User::create([
                    'name'      => $socialUser->getName(),
                    'avatar'    => $socialUser->getAvatar(),
                    'password'  => bcrypt(Str::random(8)),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => date("Y-m-d H:i:s", strtotime('now')),
                ]);
                break;
            case "apple":
                $email = $socialUser->getEmail();
                if (!isset($email)) {
                    $user = User::create([
                        'name'      => $socialUser->getName(),
                        'avatar'    => $socialUser->getAvatar(),
                        'password'  => bcrypt(Str::random(8)),
                        'email' => $socialUser->getEmail(),
                        'email_verified_at' => date("Y-m-d H:i:s", strtotime('now')),
                    ]);
                } else {
                    $name = 'user_' . Str::random(8);
                    while(!User::where('name' ,$name)->count() == 0) {
                        $name = 'user_' . Str::random(8);
                    }
                    $user = User::create([
                        'name'      => $name,
                        'avatar'    => 'https://api.dicebear.com/7.x/pixel-art/svg?seed='. bcrypt($name),
                        'password'  => bcrypt(Str::random(8)),
                        // 'email' => $socialUser->getEmail(),
                        // 'email_verified_at' => date("Y-m-d H:i:s", strtotime('now')),
                    ]);
                }
                break;
            case 'yandex':
                $user = User::create([
                    'name' => $socialUser->nickname,
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'first_name' => $socialUser->user['first_name'],
                    'last_name' => $socialUser->user['last_name'],
                    'password' => bcrypt(Str::random(8)),
                ]);

        }
        $this->addSocialAccount($provider, $user, $socialUser);

        return $user;
    }

    public function findUserBySocialId($provider, $id)
    {
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $id)
            ->first();

        return $socialAccount ? $socialAccount->user : false;
    }

    public function findUserByEmail($email)
    {
//        return User::where('email', $email)->first();
        return !$email ? null : User::where('email', $email)->first();
    }

    public function addSocialAccount($provider, $user, $socialUser): void
    {
        switch($provider) {
            case "vkontakte":
                SocialAccount::create([
                    'user_id'       => $user->id,
                    'provider'      => $provider,
                    'provider_id'   => $socialUser->getId(),
                    'token'         => $socialUser->token,
                ]);
                break;
            case "telegram":
                SocialAccount::create([
                    'user_id'       => $user->id,
                    'provider'      => $provider,
                    'provider_id'   => $socialUser->getId(),
                    'token'         => 'none',
                ]);
                break;
            case "apple":
                SocialAccount::create([
                    'user_id'       => $user->id,
                    'provider'      => $provider,
                    'provider_id'   => $socialUser->getId(),
                    'token'         => 'none',
                ]);
                break;
            case 'yandex':
                SocialAccount::create([
                    'user_id'       => $user->id,
                    'provider'      => $provider,
                    'provider_id'   => $socialUser->getId(),
                    'token'         => 'none',
                ]);
        }
    }
}
