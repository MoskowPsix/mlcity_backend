<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestEditEmailNotVerification;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
//use Illuminate\Http\Request;
use Exception;


use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Http\Requests\RequestResetPhoneVerificationCode;
use App\Http\Requests\Auth\VerficationCodeRequest;
use App\Mail\OrderCode;
use App\Models\Email;
use App\Models\Phone;
use App\Models\PhoneCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Js;

class AuthController extends Controller
{
    /**
     * Остановить валидацию после первой неуспешной проверки.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * Ркгистрация нового пользователя с отправкой кода на почту для её верификации
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $trans = DB::transaction(function () use ($request) {
                $input = $request->all();
                $pass  =  bcrypt($input['password']);
                if ($input['avatar']) {
                    $user  =  User::create([
                        'name' => $input['name'],
                        'password' => $pass,
                        'avatar' => $input['avatar'],
                        'email' => $input['email'],
                        // 'number' => $input['number'],
                    ]);
                } else {
                    $user  =  User::create([
                        'name' => $input['name'],
                        'password' => $pass,
                        'avatar' => 'https://api.dicebear.com/7.x/pixel-art/svg?seed=' . bcrypt($input['email'] . $input['name']),
                        'email' => $input['email'],
                        // 'number' => $input['number'],
                    ]);
                }

                // $this->createCodeEmail($user);

                $token = $this->getAccessToken($user);

                return response()->json([
                    'status'        => 'success',
                    'message'       => __('messages.register.success'),
                    'access_token'  => $token,
                    'token_type'    => 'Bearer',
                    'user'          => $user
                ], 200);
            }, 3);
            if ($trans) {
                return $trans;
            }
        } catch (Exception $e) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'Извините, при регистрации произошла критическая ошибка',
            ], 500);
        }
    }

    /**
     * @param VerficationCodeRequest $request
     * @return JsonResponse
     * Верификация почты с кодом подтверждения
     */
    public function verificationEmailForCode(VerficationCodeRequest $request)
    {
        $code = $request->code;
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->orderBy('created_at', 'desc')->where('last', true)->first();
        if (!empty($user) && !empty($ecode)) {
            if ((strtotime($ecode->created_at) < time())) {
                if ($ecode->code == $code) {
                    $ecode->update(['last' => false]);
                    $user->email_verified_at = date("Y-m-d H:i:s", strtotime('now'));
                    $user->save();
                    return response()->json(['status' => 'success', 'email_verification' => $user->email], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'code does not fit'], 403);
                }
            } else {
                $ecode->update(['last' => false]);
                return response()->json(['status' => 'error', 'message' => 'code has expired'], 403);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'code has not exist'], 403);
        }
    }
    /**
     * @param RequestEditEmailNotVerification $request
     * @return JsonResponse
     * Изменение не верифицированной почты
     */
    public function editEmailNotVerification(RequestEditEmailNotVerification $request): JsonResponse
    {
        $user = User::find(auth('api')->user()->id);
        if (isset($user->email_verified_at)) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Не удалось поменять почту, она уже подтверждена',
            ], 403);
        }

        User::where('id', $user->id)->update([
            'email' => $request->email,
        ]);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Почта ' . $request->email . ' успешно изменена'
        ], 201);
    }
    /**
     * @param RequestResetEmailVerificationCode $request
     * @return JsonResponse
     * Смена верифицированной почты с кодом подтверждения
     */
    public function resetEmailForCode(RequestResetEmailVerificationCode $request)
    {
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->where('last', true)->first();
        if (!empty($user) && !empty($ecode)) {
            if ($ecode->code === $request->code) {
                if ((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60 * 30)))) {
                    $user->ecode()->where(['last' => true])->update(['last' => false]);
                    $user->email = $request->email;
                    $user->email_verification = null;
                    $user->save();
                    $this->createCodeEmail($user);
                    $user->ecode()->update(['last' => false]);
                    return response()->json(['status' => 'success', 'new_email' => $user->email], 200);
                } else {
                    $ecode->update(['last' => false]);
                    return response()->json(['status' => 'error', 'message' => 'code has expired'], 403);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'code does not fit'], 403);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'code has not exist'], 403);
        }
    }
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * Вход пользователя с созданием токена
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->getCredentials();
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.login.error'),
                'cr' => $credentials,
            ], 401);
        }

        if (isset($credentials['name'])) {
            $user = User::where('name', $credentials['name'])->with('socialAccount', 'roles')->firstOrFail();
        } elseif (isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->with('socialAccount', 'roles')->firstOrFail();
        }

        $token = $this->getAccessToken($user);
        $cookie = Cookie::forever('Bearer_token', $token);

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.login.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200)->withCookie($cookie);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     * Смена пароля любого пользователя только для админа
     */
    public function resetPasswordForAdmin(Request $request)
    {
        if ($request->new_password === $request->retry_password) {
            User::where('id', $request->user_id)->update(['password' => bcrypt($request->new_password)]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password changed!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The new password does not match!'
            ], 403);
        }
    }
    /**
     * @return JsonResponse
     * Выход пользователя с удалением токена
     */
    public function logout(): JsonResponse
    {
        try {
            $cookie = Cookie::forget('Bearer_token');
            auth('api')->user()->currentAccessToken()->delete();
            Session::flush();

            return response()->json([
                'status'        => 'success',
                'message'       => __('messages.logout.success'),
            ], 200)->withCookie($cookie);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function getAccessToken($user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
    /**
     * @param User $user
     * @return string
     * Создание одноразового кода подтверждения и отправка его на почту пользователя
     */
    private function createCodeEmail(User $user): string
    {
        $user = User::where('id', $user->id)->first();
        if (!empty($user->ecode()->first())) {
            if ((strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 30) <= time()) {
                $code = rand(1000, 9999);
                $user->ecode()->create([
                    'code' => $code,
                ]);
                Mail::to($user->email)->send(new OrderCode($code));
                return 'success';
            } else {
                return 'approximate time: ' . strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 30 - time();
            }
        } else {
            $code = rand(1000, 9999);
            $user->ecode()->create([
                'code' => $code,
            ]);
            Mail::to($user->email)->send(new OrderCode($code));
            return 'success';
        }
    }
    /**
     * @return JsonResponse
     * Создания одноразого кода для почты по маршруту
     */
    public function generateCodeForEmail(): JsonResponse
    {
        $user = auth('api')->user();
        $result = $this->createCodeEmail($user);
        if ($result === 'success') {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => $result], 400);
        }
    }
}
