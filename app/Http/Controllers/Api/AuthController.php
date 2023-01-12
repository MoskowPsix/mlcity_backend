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
    public function register(RegisterRequest $request)
    {
        $input = $request->all();
        $input['password']  =  bcrypt($input['password']);

        $user  =  User::create($input);

        $userName = $user->name;
        $token = $user->createToken('auth_token')->plainTextToken;

        Auth::login($user);

        return response()->json([
            'status'        => 'success',
            'message'       => 'Вы успешно зарегистрированы!',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user_name'     => $userName
        ], 200);

    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неудачная авторизации'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $userName = $user->name;

        return response()->json([
            'status'        => 'success',
            'message'       => 'Вы успешно авторизовались!',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user_name'     => $userName
        ], 200);
    }

    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            Session::flush();
//            Auth::user()->logout();

            return response()->json([
                'status'        => 'success',
                'message'       => 'Пользователь успешно вышел'
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 401);
        }
    }
}
