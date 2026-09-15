<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateCartingIntegration
{
    public function handle(Request $request, Closure $next)
    {
        $expected = trim((string) config('services.carting.api_key', ''));
        $provided = trim((string) $request->header('X-Api-Key', ''));

        if ($expected === '' || !hash_equals($expected, $provided)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
