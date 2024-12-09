<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleModerator
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
        if (auth('api')->user()->hasRole('Moderator') || auth('api')->user()->hasRole('Admin')) {
            //$request->merge(["city"=>auth('api')->user()->city]);
            //$request->merge(["region"=>auth('api')->user()->region]);
            return $next($request);
        } elseif (auth('api')->user()->hasRole('root')){
            return $next($request);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough rights with your role!'
            ], 403);
        }
    }
}
