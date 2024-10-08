<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestEditEmailNotVerification;
use App\Http\Requests\Auth\ResetPasswordForAdminRequest;
use App\Http\Requests\Auth\VerficationCodeRequest;
use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Http\Resources\Auth\EditEmailNotVerify\ErrorEditEmailVerifyResource;
use App\Http\Resources\Auth\EditEmailNotVerify\ExistVerifyEditEmailVerifyResource;
use App\Http\Resources\Auth\EditEmailNotVerify\SuccessEditEmailVerifyResource;
use App\Http\Resources\Auth\GenerateCodeForMail\ErrorGenerateCodeForMailResource;
use App\Http\Resources\Auth\GenerateCodeForMail\SuccessGenerateCodeForMailResource;
use App\Http\Resources\Auth\Login\ErrorLoginResource;
use App\Http\Resources\Auth\Login\FailedLoginResource;
use App\Http\Resources\Auth\Login\NotFoundLoginResource;
use App\Http\Resources\Auth\Login\SuccessLoginResource;
use App\Http\Resources\Auth\Logout\FailedLogoutResource;
use App\Http\Resources\Auth\Logout\SuccessLogoutResource;
use App\Http\Resources\Auth\Register\ErrorRegisterResource;
use App\Http\Resources\Auth\Register\SuccessRegisterResource;
use App\Http\Resources\Auth\ResetEmailForCode\SuccessResetEmailForCodeResource;
use App\Http\Resources\Auth\ResetPasswordForAdmin\ErrorResetPasswordForAdminResource;
use App\Http\Resources\Auth\ResetPasswordForAdmin\SuccessResetPasswordForAdminResource;
use App\Http\Resources\Auth\VerifyEmailForCode\ErrorVerifyEmailForCodeResource;
use App\Http\Resources\Auth\VerifyEmailForCode\ExpiredVerifyEmailForCodeResource;
use App\Http\Resources\Auth\VerifyEmailForCode\InvalidVerifyEmailForCodeResource;
use App\Http\Resources\Auth\VerifyEmailForCode\NotFitVerifyEmailForCodeResource;
use App\Http\Resources\Auth\VerifyEmailForCode\SuccessVerifyEmailForCodeResource;
use App\Models\User;
use Exception;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group(name: 'Auth', description: 'Авторизация')]
class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }
    /**
     * Остановить валидацию после первой неуспешной проверки.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * @throws Exception
     */
    #[ResponseFromApiResource(SuccessRegisterResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(ErrorRegisterResource::class, null, 403)]
    #[Endpoint(title: 'Register', description: 'Регистрация нового пользователя')]
    public function register(RegisterRequest $request): SuccessRegisterResource | ErrorRegisterResource
    {
        try {
            $user = $this->authService->register($request);
            return new SuccessRegisterResource($user);
        } catch (\Exception $e) {
            return new ErrorRegisterResource([]);
        }
    }
    #[ResponseFromApiResource(SuccessLoginResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(FailedLoginResource::class, null, 403)]
    #[ResponseFromApiResource(NotFoundLoginResource::class, null, 404)]
    #[ResponseFromApiResource(ErrorLoginResource::class, null, 403)]
    #[Endpoint(title: 'Login', description: 'Авторизация пользователя')]
    public function login(LoginRequest $request):
    SuccessLoginResource |
    FailedLoginResource |
    NotFoundLoginResource |
    ErrorLoginResource
    {
        try {
            $user = $this->authService->login($request);
            return new SuccessLoginResource($user);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'Login failed' => new FailedLoginResource([]),
                'User not found' => new NotFoundLoginResource([]),
                default => new ErrorLoginResource([]),
            };
        }
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessLogoutResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(FailedLogoutResource::class, null, 500)]
    #[Endpoint(title: 'logout', description: 'Выход пользователя')]
    public function logout(): SuccessLogoutResource | FailedLogoutResource
    {
        $response = $this->authService->logout();
        return $response ? new SuccessLogoutResource([]) : new FailedLogoutResource([]);
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessVerifyEmailForCodeResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(InvalidVerifyEmailForCodeResource::class, null, 403)]
    #[ResponseFromApiResource(ExpiredVerifyEmailForCodeResource::class, null, 403)]
    #[ResponseFromApiResource(NotFitVerifyEmailForCodeResource::class, null, 403)]
    #[ResponseFromApiResource(ErrorVerifyEmailForCodeResource::class, null, 500)]
    #[Endpoint(title: 'VerifyEmailForCode', description: 'Подтверждения почты пользователя по коду')]
    public function verifyEmailForCode(VerficationCodeRequest $request):
    SuccessVerifyEmailForCodeResource |
    NotFitVerifyEmailForCodeResource |
    ExpiredVerifyEmailForCodeResource |
    InvalidVerifyEmailForCodeResource |
    ErrorVerifyEmailForCodeResource
    {
        try {
            $user = $this->authService->verificationEmailForCode($request);
            return new SuccessVerifyEmailForCodeResource($user);
        } catch (Exception $e) {
            return match ($e->getMessage()) {
                'Invalid Code' => new InvalidVerifyEmailForCodeResource([]),
                'Code has expired' => new ExpiredVerifyEmailForCodeResource([]),
                'Code does not fit' => new NotFitVerifyEmailForCodeResource([]),
                default => new ErrorVerifyEmailForCodeResource([]),
            };
        }
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessEditEmailVerifyResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(ErrorEditEmailVerifyResource::class, null, 500)]
    #[ResponseFromApiResource(ExistVerifyEditEmailVerifyResource::class, null, 403)]
    #[Endpoint(title: 'EditEmailNotVerify', description: 'Смена не подтверждённой почты')]
    public function editEmailNotVerify(RequestEditEmailNotVerification $request):
    SuccessEditEmailVerifyResource |
    ErrorEditEmailVerifyResource |
    ExistVerifyEditEmailVerifyResource
    {
        try {
            $user = $this->authService->editEmailNotVerification($request);
            return new SuccessEditEmailVerifyResource($user);
        } catch (Exception $e) {
            return match ($e->getMessage()) {
                'Email verified' => new ExistVerifyEditEmailVerifyResource([]),
                default => new ErrorEditEmailVerifyResource([]),
            };

        }
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessResetEmailForCodeResource::class, User::class, collection: false)]
    #[ResponseFromApiResource(InvalidVerifyEmailForCodeResource::class, null, 500)]
    #[ResponseFromApiResource(ErrorVerifyEmailForCodeResource::class, null, 403)]
    #[ResponseFromApiResource(NotFitVerifyEmailForCodeResource::class, null, 403)]
    #[ResponseFromApiResource(ExpiredVerifyEmailForCodeResource::class, null, 403)]
    #[Endpoint(title: 'ResetEmailForCode', description: 'Смена подтверждённой почты')]
    public function resetEmailForCode(RequestResetEmailVerificationCode $request):
    SuccessResetEmailForCodeResource |
    InvalidVerifyEmailForCodeResource |
    ErrorVerifyEmailForCodeResource |
    NotFitVerifyEmailForCodeResource |
    ExpiredVerifyEmailForCodeResource
    {
        try {
            $user = $this->authService->resetEmailForCode($request);
            return new SuccessResetEmailForCodeResource($user);
        } catch (Exception $e) {
            return match ($e->getMessage()) {
                'Invalid Code' => new InvalidVerifyEmailForCodeResource([]),
                'Code has expired' => new ExpiredVerifyEmailForCodeResource([]),
                'User or code not found' => new NotFitVerifyEmailForCodeResource([]),
                default => new ErrorVerifyEmailForCodeResource([]),
            };
        }
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessResetPasswordForAdminResource::class, null, )]
    #[ResponseFromApiResource(ErrorResetPasswordForAdminResource::class, null, 500)]
    #[Endpoint(title: 'ResetPasswordForAdmin', description: 'Смена пароля пользоватя для Админа')]
    public function resetPasswordForAdmin(ResetPasswordForAdminRequest $request): SuccessResetPasswordForAdminResource | ErrorResetPasswordForAdminResource
    {
       try {
           $this->authService->resetPasswordForAdmin($request);
           return new SuccessResetPasswordForAdminResource([]);
       } catch (\Exception $e) {
            return new ErrorResetPasswordForAdminResource([]);
       }
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessGenerateCodeForMailResource::class, null)]
    #[ResponseFromApiResource(ErrorGenerateCodeForMailResource::class, null, 500)]
    #[Endpoint(title: 'GenerateCodeForEmail', description: 'Отправка кода для смены или подтверждения почты на почту')]
    public function generateCodeForEmail(): SuccessGenerateCodeForMailResource | ErrorGenerateCodeForMailResource
    {
            $response = $this->authService->generateCodeForEmail();
            return $response ? new SuccessGenerateCodeForMailResource([]) : new ErrorGenerateCodeForMailResource([]);
    }
}
