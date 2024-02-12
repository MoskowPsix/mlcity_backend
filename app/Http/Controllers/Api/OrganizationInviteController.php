<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use Illuminate\Http\Request;

class OrganizationInviteController extends Controller
{
    public function acceptInvite($token){
        $invite = OrganizationInvite::where("token", $token)->get();
        $organization = Organization::find($invite->organization_id)->get();

        $organization->users()->attach($invite->user_id);

        return "Вы были добавлены";
    }
}
