<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;

class CheckUserRoleInOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $authUser = auth("api")->user();
        $organizationId = $request->route("organizationId");
        $authUserPermissions =$authUser->permissionsInOrganization()->where("organization_id", $organizationId)->get();

        $organization = Organization::find($organizationId);

        if($authUser->id != $organization->user_id || !$authUserPermissions->contains("name",$role)){
            return response()->json(["message"=>"You don't have permission to change it"], 403);
        }
        return $next($request);
    }
}
