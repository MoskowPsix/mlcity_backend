<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Str;

class SocialService {

//    public function saveSocialData($user, $provider): \Illuminate\Http\JsonResponse
//    {
//
//        $user = $this->findOrCreateUser($user, $provider);
////
////        $email      = $user->getEmail();
////        $name       = $user->getName();
////        $avatar     = $user->getAvatar();
////        $password   = bcrypt(Str::random(8)); // Делаем случайный пароль, в кабинете предложить изменить.
////        $vk_id      = $user->getId(); // Нужен будет чтобы посты тянуть. Потом можно вынести в отдельную таблицу и сделать связь OneToMany
////
////        $data = [
////                'email'     => $email,
////                'name'      => $name,
////                'avatar'    => $avatar,
////                'password'  => $password,
////                'vk_id'     => $vk_id
////            ];
////
////        $user = User::where('email', $email)->first();
////        return $user ? $user->fill(['name' => $name ,'avatar' => $avatar ]) : User::create($data);
//    }
    public function findOrCreateUser($socialUser, $provider): bool
    {
        if ($user = $this->findUserBySocialId($provider, $socialUser->getId())) {
            return $user;
        }

        if ($user = $this->findUserByEmail($socialUser->getEmail())) {
            $this->addSocialAccount($provider, $user, $socialUser);
            return $user;
        }

        $user = User::create([
            'email'     => $user->getEmail(),
            'name'      => $user->getName(),
            'avatar'    => $user->getAvatar(),
            'password'  => bcrypt(Str::random(8)),
        ]);

        $this->addSocialAccount($provider, $user, $socialUser);

        return $user;
    }

    public function findUserBySocialId($provider, $id): bool
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
        SocialAccount::create([
            'user_id'       => $user->id,
            'provider'      => $provider,
            'provider_id'   => $socialUser->getId(),
            'token'         => $socialUser->token,
        ]);
    }
}
