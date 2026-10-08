<?php

namespace App\Http\Middleware;

use App\Models\EventCommissionMember;
use Closure;
use Illuminate\Http\Request;

class CheckpointAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        $shared = $user && EventCommissionMember::where('user_id', $user->id)->exists();
        abort_unless($user?->checkpoint_access || $shared, 403, 'Нет доступа к Checkpoint.');
        $request->setUserResolver(fn () => $user);
        return $next($request);
    }
}
