<?php

namespace App\Contracts\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestEditEmailNotVerification;
use App\Http\Requests\Auth\ResetPasswordForAdminRequest;
use App\Http\Requests\Auth\VerficationCodeRequest;
use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;


interface AuthServiceInterface
{
    public function register(RegisterRequest $request): User;
    /**
     * @param LoginRequest $request
     * @return User
     * Вход пользователя с созданием токена
     */
    public function login(LoginRequest $request): User;

    /**
     * @return bool
     * Выход пользователя с удалением токена
     */
    public function logout(): bool;
    /**
     * @param VerficationCodeRequest $request
     * @return User
     * Верификация почты с кодом подтверждения
     */
    public function verificationEmailForCode(VerficationCodeRequest $request): User;
    /**
     * @param RequestEditEmailNotVerification $request
     * @return User
     * Изменение не верифицированной почты
     */
    public function editEmailNotVerification(RequestEditEmailNotVerification $request): User;
    /**
     * @param RequestResetEmailVerificationCode $request
     * @return User
     * Смена верифицированной почты с кодом подтверждения
     */
    public function resetEmailForCode(RequestResetEmailVerificationCode $request): User;
    /**
     * @param ResetPasswordForAdminRequest $request
     * @return bool
     * Смена пароля любого пользователя только для админа
     */
    public function resetPasswordForAdmin(ResetPasswordForAdminRequest $request): bool;
    /**
     * @param User $user
     * @return string
     * Создание токена авторизации для пользователя
     */
    public function getAccessToken(User $user): string;
    /**
     * @return bool
     * Создания одноразого кода для почты по маршруту
     */
    public function generateCodeForEmail(): bool;
}
