<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sight;
use App\Models\SightInvite;
use Illuminate\Http\Request;

class SightInviteController extends Controller
{
    public function acceptInvite(Request $request){
        $invite = SightInvite::where("token", $request->token)->first();
        $sight = Sight::find($invite->sight_id);

        $permissions = $invite->userPermissions;
        foreach($permissions as $permission){
            $sight->users()->attach($invite->user_id, [
                "permission_id" => $permission->id
            ]);
        }


        return response()->json(["Вы были добавлены"], 200);
    }
}
