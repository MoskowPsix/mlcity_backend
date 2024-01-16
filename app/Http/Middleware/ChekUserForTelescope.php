<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChekUserForTelescope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!isset($request->token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not token'
            ], 403);
        }
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->token);
        if (!isset($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token is not corrected'
            ], 403);
        }
        $user = $token->tokenable;
        if (!isset($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 403);
        }
        if ($user->hasRole('root')) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Role is not corrected'
            ], 403);
        }
    }
}
