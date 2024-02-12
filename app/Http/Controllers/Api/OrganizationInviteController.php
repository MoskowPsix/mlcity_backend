<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use Illuminate\Http\Request;

class OrganizationInviteController extends Controller
{
    public function acceptInvite(Request $request){
        $invite = OrganizationInvite::where("token", $request->token)->first();
        $organization = Organization::find($invite->organization_id);

        $organization->users()->attach($invite->user_id);

        return response()->json(["Вы были добавлены"], 200);
    }
}
