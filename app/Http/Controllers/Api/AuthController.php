<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
//use Illuminate\Http\Request;

use App\Http\Requests\RequestResetEmailVerificationCode;
use App\Http\Requests\RequestResetPhoneVerificationCode;
use App\Http\Requests\Auth\VerficationCodeRequest;
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
        $pass  =  bcrypt($input['password']);
        if ($input['avatar']) {
            $user  =  User::create([
                'name'=> $input['name'],
                'password'=> $pass,
                'avatar'=> $input['avatar'],
                'email' => $input['email'],
                'number' => $input['number'],
            ]);
        } else {
            $user  =  User::create([
                'name'=> $input['name'],
                'password'=> $pass,
                'avatar'=> 'https://api.dicebear.com/7.x/pixel-art/svg?seed='. bcrypt($input['email'] . $input['name']),
                'email' => $input['email'],
                'number' => $input['number'],
            ]);
        }

        try{
        $this->createCodeEmail($user);
        // $this->createCodePhone($user);
        } catch (Exception $e) {
            User::findI($user->id)->delete();
            return response()->json([
                'status'        => 'error',
                'message'       => 'email error',
            ], 422);
        }

        $token = $this->getAccessToken($user);

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.register.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200);

    }

    

    public function verificationCodeEmail(VerficationCodeRequest $request)
    {
        $code=$request->validated();
        $code = $request->code;
        // info($code);
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->orderBy('created_at', 'desc')->where('last', true)->first();
        // info($user);
        if (!empty($user) && !empty($ecode)) {
            if ((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60*30)))) {
                if ($ecode->code == $code) {
                    $ecode->update(['last' => false]);
                    $user->email_verified_at = date("Y-m-d H:i:s", strtotime('now'));
                    $user->save();
                    return response()->json(['status'=> 'success', 'email_verification' => $user->email], 200);
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
    }
    
    public function resetEmail(RequestResetEmailVerificationCode $request) 
    {
        //$user = auth('api')->user();
        $user = User::find(auth('api')->user()->id);
        $ecode = $user->ecode()->where('last', true)->first();
        if (!empty($user) && !empty($ecode)) {
            if ($ecode->code === $request->code) {
                if ((strtotime($ecode->created_at) < time()) && (time() < (strtotime($ecode->created_at) + (60*30)))) {
                    $user->ecode()->where(['last' => true])->update(['last' => false]);
                    $user->email = $request->email;
                    $user->email_verification = null;
                    $user->save();
                    $this->createCodeEmail($user);
                    $user->ecode()->update(['last' => false]);
                    return response()->json(['status'=> 'success','new_email'=> $user->email],200);
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

    public function resetPhone(VerficationCodeRequest $request)
    {
        $user = User::find(auth('api')->user())->orderBy('created_at', 'desc')->first();
        $pcode = $user->pcode()->where('last', true)->orderBy('created_at', 'desc')->first();
        if (!empty($user) && !empty($ecode)) {
            if ($pcode->code === $request->code) {
                if ((strtotime($pcode->created_at) < time()) && (time() < (strtotime($pcode->created_at) + (60*30)))) {
                    $user->pcode()->where(['last' => true])->update(['last' => false]);
                    $user->naumber = $request->number;
                    $user->naumber_verified_at = null;
                    $user->save();
                    $this->createCodePhone($user);
                    return response()->json(['status'=> 'success','new_email'=> $user->number],200);
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
        // if (!Auth::attempt($request->only('email', 'password'))) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => __('messages.login.error')
        //     ], 401);
        // }
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

    private function createCodePhone($user)
    {
        $user =  User::where('id', $user->id)->first();
        if(!empty($user->pcode()->first())) {
            if ((strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 120)  < time()) {
                $code = rand(1000, 9999);
                $user->pcode()->create([
                    'code'=> $code,
                ]);
                return 'success';
            } else {
                return strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at)+120-time();
            }
        } else {
            $code = rand(1000, 9999);
            $user->pcode()->create([
                'code'=> $code,
            ]);
            return 'success';
        }
    }
    private function createCodeEmail($user)
    {
        $user = User::where('id', $user->id)->first();
        if (!empty($user->ecode()->first())) {
            if ((strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at) + 120) <= time()) {
                $code = rand(1000, 9999);
                $user->ecode()->create([
                    'code'=> $code,
                ]);
                Mail::to($user->email)->send(new OrderCode($code));
                return 'success';
            } else {
                return 'approximate time: '.strtotime($user->ecode()->orderBy('created_at', 'desc')->first()->created_at)+120-time();
            }
        } else {
            $code = rand(1000, 9999);
                $user->ecode()->create([
                    'code'=> $code,
                ]);
                Mail::to($user->email)->send(new OrderCode($code));
                return 'success';
        }
    }
    public function verificationCodePhone($code)
    {
        if (999 <= $code && $code <= 10000) {
            $user = User::where('id', auth('api')->user()->id)->first();
            $pcode = $user->pcode()->orderBy('created_at', 'desc')->first();
            if (!empty($email) && !empty($pcode)) {
                if ((strtotime($pcode->created_at) < time()) && (time() < (strtotime($pcode->created_at) + (60*30)))) {
                    if ($pcode->code === $code) {
                        $pcode->update(['last' => false]);
                        $user->number_verified_at = date("Y-m-d H:i:s", strtotime('now'));
                        $user->save();
                        return response()->json(['status'=> 'success', 'phone_verification' => $user->phone], 200);
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
}

