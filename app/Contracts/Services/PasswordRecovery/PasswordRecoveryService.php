<?php

namespace App\Contracts\Services\PasswordRecovery;

use App\Contracts\Services\PasswordRecovery\PasswordRecoveriServiceInterface;
use App\Http\Requests\RecoveryPassword\RecoveryPasswordByCode;
use App\Http\Requests\RecoveryPassword\SendRecoveryPassword;
use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use App\Notifications\PasswordRecovery;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException as QueryExceptionAlias;
use Illuminate\Support\Facades\Mail;

class PasswordRecoveryService implements PasswordRecoveriServiceInterface
{
    /**
     * @throws \Exception
     */
    public function send(SendRecoveryPassword $request): bool
    {
        $email = $request->email;
        if (!User::where('email', $email )->exists()) {
            throw new \Exception('Mail not found');
        }
        try {
            auth('api')->user()->notify(new PasswordRecovery());
        } catch (Exception $ex) {
            throw new \Exception('Mail not send');
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function recovery(RecoveryPasswordByCode $request): bool
    {
        $data = explode(',', decrypt($request->code));
        $pass = $request->password;
        $date_now = Carbon::now();
        $date_code = Carbon::parse($data[0])->addDays(1);
        if (!($date_now < $date_code)) {
            throw new Exception('The code has expired');
        }

        $user_id = $data[2];
        $user = User::find($user_id);
        empty($user) ? throw new Exception('The user not exist') : null;
        $user->update([
            "password" => bcrypt($pass)
        ]);
        return true;
    }


    private function sendMail($mail, $url): void
    {
        Mail::to($mail)->send(new RecoveryPasswordMail($url));
    }

}
