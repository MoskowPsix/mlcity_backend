<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
//use Illuminate\Http\Request;

use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Http\Requests\RequestResetPhoneVerificationCode;
use App\Mail\OrderCode;
use App\Models\Email;
use App\Models\Phone;
use App\Models\PhoneCode;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Остановить валидацию после первой неуспешной проверки.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    /**
     * @OA\Post(
     ** path="/register",
     *   tags={"Auth"},
     *   summary="Register",
     *   operationId="register",
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="city",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="region",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $input['password']  =  bcrypt($input['password']);

        $user  =  User::create([
            'name'=> $input['name'],
            'password'=> bcrypt($input['password']),
            'avatar'=> $input['avatar'],
        ]);

        $user->email()->create([
            'email' => $input['email'],
        ]);
        $this->createCodeEmail($user);

        if($input['number']) {
            $user->phone()->create([
                'number' => $input['number'],
            ]);
        }
        // $this->createCodePhone($user);

        $token = $this->getAccessToken($user);

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.register.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200);

    }

    public function verificationCodePhone($code)
    {
        if (999 <= $code && $code <= 10000) {
            $phone = Phone::where('user_id', auth('api')->user()->id)->first();
            $pcode = $phone->pcode()->orderBy('created_at', 'desc')->first();
            if (!empty($email) && !empty($pcode)) {
                if ((strtotime($pcode->created_at) < time()) && (time() < (strtotime($pcode->created_at) + (60*30)))) {
                    if ($pcode->code === $code) {
                        $pcode->update(['last' => false]);
                        $phone->update(['verification' => true]);
                        return response()->json(['status'=> 'success', 'email_verification' => $phone], 200);
                    } else {
                        return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
                    }
                } else {
                    $pcode->update(['last' => false]);
                    return response()->json(['status'=> 'error','message'=> 'code has expired'],403);
                }
            } else {
                return response()->json(['status'=> 'error','message'=> 'code has not exist'],403);
            }
        } else {
            return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
        }
    }

    public function verificationCodeEmail($code)
    {
        if (999 <= $code && $code <= 10000) {
            $email = Email::where('user_id', auth('api')->user()->id)->first();
            $ecode = $email->ecode()->orderBy('created_at', 'desc')->where('last', true)->first();
            if (!empty($email) && !empty($ecode)) {
                if ((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60*30)))) {
                    $ecode->update(['last' => false]);
                    if ($ecode->code === $code) {
                        $email->update(['verification' => true]);
                        return response()->json(['status'=> 'success', 'email_verification' => $email], 200);
                    } else {
                        return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
                    }
                } else {
                    $ecode->update(['last' => false]);
                    return response()->json(['status'=> 'error','message'=> 'code has expired'],403);
                }
            } else {
                return response()->json(['status'=> 'error','message'=> 'code has not exist'],403);
            }
        } else {
            return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
        }
    }
    public function verificationEmail() 
    {
        $user = auth('api')->user();
        $result = $this->createCodeEmail($user);
        if ($result === 'success') {
            return response()->json(['status'=> 'success'],200);
        } else {
            return response()->json(['status'=> 'error','message' => $result],200);
        }
    }
    public function resetEmail(RequestResetEmailVerificationCode $request) 
    {
        $user = auth('api')->user();
        $email = Email::where('user_id', $user->id)->first();
        $ecode = $email->ecode()->where('last', true)->first();
        if (!empty($email) && !empty($ecode)) {
            if ($ecode->code === $request->code) {
                if ((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60*30)))) {
                    User::find($user->id)->email()->orderBy('created_at', 'desc')->firstOrFail()->ecode()->where(['last' => true])->update(['last' => false]);
                    User::find($user->id)->email()->orderBy('created_at', 'desc')->first()->delete();
                    User::find($user->id)->email()->create([
                        'email' => $request->email,
                    ]);
                    $this->createCodeEmail($user);
                    $email->ecode()->update(['last' => false]);
                    return response()->json(['status'=> 'success','new_email'=> $email],200);
                } else {
                    $ecode->update(['last' => false]);
                    return response()->json(['status'=> 'error','message'=> 'code has expired'],403);
                }
            } else {
                return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
            }
        } else {
            return response()->json(['status'=> 'error','message'=> 'code has not exist'],403);
        }
    }
    public function verificationPhone() 
    {
        $user = auth('api')->user();
        $result = $this->createCodePhone($user);
        if ($result === 'success') {
            return response()->json(['status'=> 'success'],200);
        } else {
            return response()->json(['status'=> 'error','approximate_time' => $result],200);
        }
    }

    public function resetPhone(RequestResetPhoneVerificationCode $request)
    {
        $user = auth('api')->user();
        $phone = phone::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
        $pcode = $phone->pcode()->where('last', true)->orderBy('created_at', 'desc')->first();
        if (!empty($email) && !empty($ecode)) {
            if ($pcode->code === $request->code) {
                if ((strtotime($pcode->created_at) < time()) && (time() < (strtotime($pcode->created_at) + (60*30)))) {
                    User::find($user->id)->phone()->orderBy('created_at', 'desc')->firstOrFail()->pcode()->where(['last' => true])->update(['last' => false]);
                    User::find($user->id)->phone()->orderBy('created_at', 'desc')->first()->delete();
                    User::find($user->id)->phone()->create([
                        'number' => $request->number,
                    ]);
                    $this->createCodePhone($user);
                    return response()->json(['status'=> 'success','new_email'=> $phone],200);
                } else {
                    $pcode->update(['last' => false]);
                    return response()->json(['status'=> 'error','message'=> 'code has expired'],403);
                }
            } else {
                return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
            }
        } else {
            return response()->json(['status'=> 'error', 'message' => 'code does not fit'], 403);
        }
    }
    private function createCodePhone($user)
    {
        $phone =  Phone::where('user_id', $user->id)->first();
        $phone->pcode()->update(['last' => false]);
        if(!empty($phone->pcode()->first())) {
            if ((strtotime($phone->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 120)  < time()) {
                $code = rand(1000, 9999);
                $phone->pcode()->create([
                    'code'=> $code,
                ]);
                return 'success';
            } else {
                return strtotime($phone->ecode()->orderBy('created_at', 'desc')->first()->created_at)+120-time();
            }
        } else {
            $code = rand(1000, 9999);
            $phone->pcode()->create([
                'code'=> $code,
            ]);
            return 'success';
        }
    }
    private function createCodeEmail($user)
    {
        $email = Email::where('user_id', $user->id)->first();
        $email->ecode()->update(['last' => false]);
        if (!empty($email->ecode()->first())) {
            if ((strtotime($email->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 120) <= time()) {
                $code = rand(1000, 9999);
                $email->ecode()->create([
                    'code'=> $code,
                ]);
                Mail::to($email->email)->send(new OrderCode($code));
                return 'success';
            } else {
                return 'approximate time: '.strtotime($email->ecode()->orderBy('created_at', 'desc')->first()->created_at)+120-time();
            }
        } else {
            $code = rand(1000, 9999);
                $email->ecode()->create([
                    'code'=> $code,
                ]);
                Mail::to($email->email)->send(new OrderCode($code));
                return 'success';
        }
    }
      /**
     * @OA\Post(
     ** path="/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
     public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.login.error')
            ], 401);
        }

        $user = User::where('email', $request->email)->orWhere('email', $request->name)->with('socialAccount')->firstOrFail();
        $token = $this->getAccessToken($user);

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.login.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200);
    }

     /**
     * @OA\Put(
     ** path="/reset_password",
     *   tags={"Auth"},
     *   summary="reset password",
     *   operationId="reset_password",
     *
     *   @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="retry_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function resetPasswordTokens(Request $request) {
        // if (!Auth::attempt($request->only('password', 'password_retry'))) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => __('messages.login.error')
        //     ], 401);
        // }
        $validated = $request->validate([
            'password'        => 'required|min:8|max:255',
            'password_retry'  => 'required|min:8|max:255',
        ]);
        if ($request->password === $request->password_retry) {
            $user = User::where('id', auth('api')->user()->id)->with('socialAccount')->firstOrFail();
            $user->update(['password' => bcrypt($request->password)]);

            return response()->json([
                'status'        => 'success',
                'message'       => 'Password set',
                'user'          => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The new password does not match!'
            ], 403);
        }
    }
     public function resetPassword(Request $request)
    {
        if (password_verify($request->old_password, auth('api')->user()->password)) {
            if ($request->new_password === $request->retry_password) {
                $user = User::where('id', auth('')->user()->id)->firstOrFail();
                User::where('id', auth('api')->user()->id)->update(['password' => bcrypt($request->new_password)]);
                return response()->json([
                    'status'        => 'success',
                    'message'       => __('messages.login.success'),
                    'user'          => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The new password does not match!'
                ], 403);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The old password is incorrect!'
            ], 403);
        }

    }
    /**
     * @OA\Put(
     ** path="/reset_password_user",
     *   tags={"Auth"},
     *   summary="reset password user",
     *   operationId="reset_password_user",
     *   @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="retry_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function resetPasswordForAdmin(Request $request) {
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

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            auth('api')->user()->currentAccessToken()->delete();
            Session::flush();

            return response()->json([
                'status'        => 'success',
                'message'       => __('messages.logout.success')
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    public function getAccessToken($user){
        return $user->createToken('auth_token')->plainTextToken;
    }
}
