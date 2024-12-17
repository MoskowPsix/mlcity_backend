<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MoonshineCkeckRoleForLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('moonshine')->user()->hasRole('Moderator') || auth('moonshine')->user()->hasRole('Admin') || auth('moonshine')->user()->hasRole('root')) {
            return $next($request);
        } else {
            abort(403, 'Access denied');
        }
    }
}
