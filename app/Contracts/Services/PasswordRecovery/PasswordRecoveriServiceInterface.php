<?php

namespace App\Contracts\Services\PasswordRecovery;

use App\Http\Requests\RecoveryPassword\RecoveryPasswordByCode;
use App\Http\Requests\RecoveryPassword\SendRecoveryPassword;

interface PasswordRecoveriServiceInterface
{
    public function send(SendRecoveryPassword $request): bool;
    public function recovery(RecoveryPasswordByCode $request): bool;
}
