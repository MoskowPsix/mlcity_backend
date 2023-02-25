<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
//use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class AuthController extends Controller
{
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
