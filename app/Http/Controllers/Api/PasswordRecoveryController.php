<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\PasswordRecovery\PasswordRecoveryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PasswordRecovery\Recovery\ErrorExpraidCodePasswordRecoveryResource;
use App\Http\Resources\PasswordRecovery\Recovery\SuccessRecoveryCodePasswordRecoveryResource;
use App\Http\Resources\PasswordRecovery\Send\ErrorNotMailExistPasswordRecoveryResource;
use App\Http\Resources\PasswordRecovery\Send\ErrorNotMailSendPasswordRecoveryResource;
use App\Http\Resources\PasswordRecovery\Send\SuccessPasswordRecoveryResource;
use App\Http\Requests\RecoveryPassword\RecoveryPasswordByCode;
use App\Http\Requests\RecoveryPassword\SendRecoveryPassword;
use Exception;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group(name: 'PasswordRecovery', description: 'Методы для восстановления пароля')]
class PasswordRecoveryController extends Controller
{
    public function __construct(private readonly PasswordRecoveryService $passwordRecoveryService)
    {}
    #[Endpoint(title: 'send', description: 'Отправка письма с ссылкой для восстановления пароля')]
    #[BodyParam("email", "string", required: true, example: '123n@mail.ru')]
    #[ResponseFromApiResource(SuccessPasswordRecoveryResource::class)]
    #[ResponseFromApiResource(ErrorNotMailExistPasswordRecoveryResource::class, null, 404)]
    #[ResponseFromApiResource(ErrorNotMailSendPasswordRecoveryResource::class, null, 405)]
    public function sendMailRecoveryPasswordUrl(SendRecoveryPassword $request): SuccessPasswordRecoveryResource | ErrorNotMailExistPasswordRecoveryResource | ErrorNotMailSendPasswordRecoveryResource
    {
        try {
            $this->passwordRecoveryService->send($request);
        } catch (Exception $ex) {
            return match ($ex) {
                "Mail not found" => new ErrorNotMailExistPasswordRecoveryResource([]),
                default => new ErrorNotMailSendPasswordRecoveryResource([]),
            };
        }
        return new SuccessPasswordRecoveryResource([]);
    }
    #[Endpoint(title: 'recovery', description: 'Смена пароля с по коду')]
    #[ResponseFromApiResource(SuccessRecoveryCodePasswordRecoveryResource::class)]
    #[ResponseFromApiResource(ErrorNotMailExistPasswordRecoveryResource::class, null, 404)]
    #[ResponseFromApiResource(ErrorExpraidCodePasswordRecoveryResource::class, null, 403)]
    public function recoveryPasswordByCode(RecoveryPasswordByCode $request): SuccessRecoveryCodePasswordRecoveryResource | ErrorExpraidCodePasswordRecoveryResource | ErrorNotMailExistPasswordRecoveryResource
    {
        try {
            $this->passwordRecoveryService->recovery($request);
        } catch (Exception $ex) {
            if ($ex == 'The code has expired') {
                return new ErrorExpraidCodePasswordRecoveryResource([]);
            } else {
                return new ErrorNotMailExistPasswordRecoveryResource([]);
            }
        }
        return new SuccessRecoveryCodePasswordRecoveryResource([]);
    }
}
