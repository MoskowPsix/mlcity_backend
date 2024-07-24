<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\RecoveryPasswordMail;
use App\Http\Requests\RecoveryPassword\RecoveryPasswordByCode;
use App\Http\Requests\RecoveryPassword\SendRecoveryPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class PasswordRecoveryController extends Controller
{
    public function sendMailRecoveryPasswordUrl(SendRecoveryPassword $request) {
        $date = Carbon::now();
        // auth('api')->user()->id
        $email = $request->email;
        if (!User::where('email', $email )->exists()) {
            return response()->json(['status' => 'error', 'message' => 'email not exist'], 404);
        }
        $user = User::where('email', $email)->first();
        $code = encrypt($date . ',' . $email . ',' . $user->id);
        $url = env('FRONT_APP_URL') . '/recovery/' . $code;
        try {
            $this->sendMail($email, $url);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['status' => 'error', 'message' => 'mail not send'], 250);
        }

        return response()->json(['status' => 'success', 'message' => 'url to send mail', "code" => $code], 200);
    }
    public function recoveryPasswordByCode(RecoveryPasswordByCode $request) {
        $data = explode(',', decrypt($request->code));
        $pass = $request->password;
        $date_now = Carbon::now();
        $date_code = Carbon::parse($data[0])->addDays(1);
        $user_id = $data[2];

        if (!($date_now < $date_code)) {
            return response()->json(["status" => "error", "message" => "The code has expired"], 410);
        }
        $user = User::find($user_id);
        $user_updated_time = Carbon::parse($user->updated_at)->addDays(1);

        if (!($date_now > $user_updated_time)) {
            return response()->json(["test" => $date_now, $user_updated_time, "status" => "error", "message" => "The profile was changed less than 24 hours ago. The password can be changed 24 hours after the profile change."], 410);
        }
        $user->update([
            "password" => bcrypt($pass)
        ]);
        return response()->json(["status" => "success", "message" => "password recovery true", "test" => $date_now, $user_updated_time], 200);
    }

    private function sendMail($mail, $url) {
        Mail::to($mail)->send(new RecoveryPasswordMail($url));
    }
}
