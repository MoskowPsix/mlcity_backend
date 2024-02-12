<?php

namespace App\Http\Controllers\Api;

use App\Filters\Organization\OrganizationAddress;
use App\Filters\Organization\OrganizationDescription;
use App\Filters\Organization\OrganizationId;
use App\Filters\Organization\OrganizationInn;
use App\Filters\Organization\OrganizationKpp;
use App\Filters\Organization\OrganizationName;
use App\Filters\Organization\OrganizationNumber;
use App\Filters\Organization\OrganizationOgrn;
use App\Filters\Organization\OrganizationUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Organization\CreateOrganisation;
use App\Mail\OrganizationInvite as MailOrganizationInvite;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
class OrganizationController extends Controller
{
    public function index(Request $request) {
        $total = 0;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;

        $organizations = Organization::query();

        $response =
            app(Pipeline::class)
            ->send($organizations)
            ->through([
                OrganizationName::class,
                OrganizationId::class,
                OrganizationInn::class,
                OrganizationKpp::class,
                OrganizationOgrn::class,
                OrganizationAddress::class,
                OrganizationDescription::class,
                OrganizationNumber::class,
                OrganizationUser::class
            ])
            ->via("apply")
            ->then(function ($organizations){
                $organizations = $organizations->get();
                return $organizations;
            });

            return response()->json(["organizations"=>["data"=>$response]],200);
    }
    public function show($id) {
        $organization = Organization::find($id);

        return response()->json(["organizations"=>["data"=>$organization]],200);
    }

    public function store(CreateOrganisation $request) {
        $data = $request->validated();
        info($data);
        $organization = Organization::create($data);

        return response()->json(["organization"=>$organization, "message"=>"success"], $status=201);
    }
    public function delete($id) {

    }

    public function getUsersOfOrganization($organizationId){
        $organizationWithUsers = Organization::where("id",$organizationId)->with("users")->get();

        return response()->json([["organizations"=>["data"=>$organizationWithUsers], "message"=>"success"], 200]);
    }



    public function addUserToOrganization($organizationId, $userId, Request $request){
        $invitedUserEmail = User::find($userId)->email;
        do {
            $token = Str::random(40);
            $token = bcrypt($token);
            $url = URL::temporarySignedRoute("organizationInvite.accept", now()->addDays(3),["token"=>$token]);
        }
        while (OrganizationInvite::where("url", $token)->first());

        $invite = OrganizationInvite::create([
            "email" => $invitedUserEmail,
            "url" => $url,
            "user_id" => $userId,
            "organization_id" => $organizationId,
            "token" => $token,
        ]);

        Mail::to($invitedUserEmail)->send(new MailOrganizationInvite($invite));

        return response()->json(["message"=>"success"],200);
    }
}
