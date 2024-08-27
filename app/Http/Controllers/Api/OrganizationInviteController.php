<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SightInvite;
use Illuminate\Http\Request;

class OrganizationInviteController extends Controller
{
    public function acceptInvite(Request $request){
        $invite = SightInvite::where("token", $request->token)->first();
        $organization = Organization::find($invite->sight_id);

        $permissions = $invite->userPermissions;
        info($permissions->toArray());
        foreach($permissions as $permission){
            $organization->users()->attach($invite->user_id, [
                "permission_id" => $permission->id
            ]);
        }


        return response()->json(["Вы были добавлены"], 200);
    }
}
