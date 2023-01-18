<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;

class SocialService {

    public function saveSocialData($user): \Illuminate\Http\JsonResponse
    {
        $email      = $user->getEmail();
        $name       = $user->getName();
        $avatar     = $user->getAvatar();
        $password   = bcrypt(Str::random(8)); // Делаем случайный пароль, в кабинете предложить изменить.
        $vk_id      = $user->getId(); // Нужен будет чтобы посты тянуть. Потом можно вынести в отдельную таблицу и сделать связь OneToMany

        $data = [
                'email'     => $email,
                'name'      => $name,
                'avatar'    => $avatar,
                'password'  => $password,
                'vk_id'     => $vk_id
            ];

        $user = User::where('email', $email)->first();
        return $user ? $user->fill(['name' => $name ,'avatar' => $avatar ]) : User::create($data);
    }
}
