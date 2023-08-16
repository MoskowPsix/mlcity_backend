<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
//use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class AuthController extends Controller
{
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

        $user  =  User::create($input);

        $token = $this->getAccessToken($user);

//        Auth::login($user);

        return response()->json([
            'status'        => 'success',
            'message'       => __('messages.register.success'),
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user
        ], 200);

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
        $user = User::where('email', $request->email)->firstOrFail();
//        $user = Auth::user();
////        $user = User::where('email', $request->email)->first();
////
////        if (!$user || $request->password != $user->password) {
////            return response()->json([
////                'status' => 'error',
////                'message' => __('messages.login.error.')
////            ], 401);
////        }

        $token = $this->getAccessToken($user);

        //$userName = $user->name;

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
    public function resetPassword(Request $request)
    {
        if (password_verify($request->old_password, auth('api')->user()->password)) {
            if ($request->new_password === $request->retry_password) {
                User::where('id', auth('api')->user()->id)->update(['password' => bcrypt($request->new_password)]);
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

    public function logout($id): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::where($id);
            //Auth::user()->tokens()->delete();
            $user->tokens()->delete();
            Session::flush();
//            Auth::user()->logout();

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
