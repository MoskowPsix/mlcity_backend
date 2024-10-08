<?php

namespace App\Contracts\Services\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestEditEmailNotVerification;
use App\Http\Requests\Auth\ResetPasswordForAdminRequest;
use App\Http\Requests\Auth\VerficationCodeRequest;
use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthService implements AuthServiceInterface
{

    /**
     * @throws Exception
     */
    public function register(RegisterRequest $request): User
    {
            $trans = DB::transaction(function () use ($request) {
                $input = $request->all();
                $pass  =  bcrypt($input['password']);
                return User::create([
                    'name' => $input['name'],
                    'password' => $pass,
                    'avatar' => 'https://api.dicebear.com/7.x/pixel-art/svg?seed=' . bcrypt($input['email'] . $input['name']),
                    'email' => $input['email'],
                ]);
            }, 3);
            return $trans ?? throw new Exception('Register failed trnsaction');
    }

    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): User
    {
        $user = User::where('email', $request->name)->first();
        isset($user) ? null : throw new Exception('User not found');

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new Exception('Login failed');
        }
        return $user;
    }
    public function logout(): bool
    {
        try {
            $cookie = Cookie::forget('Bearer_token');
            auth('api')->user()->currentAccessToken()->delete();
            Session::flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function verificationEmailForCode(VerficationCodeRequest $request): User
    {
        $code = $request->code;
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->orderBy('created_at', 'desc')->where('last', true)->first();
        if (empty($user) || empty($ecode) || $user->email_verified_at) {
            throw new \Exception('Invalid Code');
        }
        if (!(strtotime($ecode->created_at) < time())) {
            $ecode->delete();
            throw new \Exception('Code has expired');
        }
        if ($ecode->code != $code) {
            throw new \Exception('Code does not fit');
        }
        $ecode->update(['last' => false]);
        $user->email_verified_at = date("Y-m-d H:i:s", strtotime('now'));
        $user->markEmailAsVerified();
        User::where('email', '=', $user->email)->whereNull('email_verified_at')->forceDelete();
        $user->save();
        return $user;
    }

    /**
     * @throws Exception
     */
    public function editEmailNotVerification(RequestEditEmailNotVerification $request): User
    {
        $user = User::find(auth('api')->user()->id);
        if (isset($user->email_verified_at)) {
            throw new Exception('Email verified');
        }
        return $user->update([
            'email' => $request->email,
        ]);
    }

    /**
     * @throws Exception
     */
    public function resetEmailForCode(RequestResetEmailVerificationCode $request): User
    {
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->where('last', true)->first();
        if (empty($user) && empty($ecode)) {
            throw new \Exception('User or code not found');
        }
        if ($ecode->code !== $request->code) {
            throw new \Exception('Invalid Code');
        }
        if (!((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60 * 30))))) {
            $ecode->delete();
            throw new \Exception('Code has expired');
        }
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->save();
            $user->notify(new VerifyEmail());
            return $user;
    }

    public function resetPasswordForAdmin(ResetPasswordForAdminRequest $request): bool
    {
        User::where('id', $request->user_id)->update(['password' => bcrypt($request->new_password)]);
        return true;
    }

    public function getAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function generateCodeForEmail(): bool
    {
        try {
            auth('api')->user()->notify(new VerifyEmail());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
