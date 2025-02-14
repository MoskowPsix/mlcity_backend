<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\OrganizationService\OrganizationService;
use App\Filters\Organization\OrganizationDescription;
use App\Filters\Organization\OrganizationLocationFilter;
use App\Filters\Organization\OrganizationName;
use App\Filters\Organization\OrganizationUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\Organization\Delete\FailedDeleteOrganizationResource;
use App\Http\Resources\Organization\Delete\SuccessDeleteOrganizationResource;
use App\Http\Resources\Organization\OrganizationTransferUser\NotFoundOrganizationTransferUserResource;
use App\Http\Resources\Organization\OrganizationTransferUser\NotPermissionOrganizationTransferUserResource;
use App\Http\Resources\Organization\OrganizationTransferUser\SuccessOrganizationTransferUserResource;
use Illuminate\Http\Request;
use App\Http\Requests\Organization\CreateOrganizationRequest;
use App\Http\Requests\Organization\IndexOrganizationRequest;
use App\Http\Resources\Organization\getUserOrganizations\GetUserOrganizationsOrganizationSuccessResource;
use App\Http\Resources\Organization\Store\StoreOrganizationSuccessResource;
use App\Http\Resources\Organization\Show\ShowOrganizationSuccessResource;
use App\Http\Resources\Organization\Index\IndexOrganizationResource;
use App\Http\Resources\Organization\Store\StoreOrganizationNoAuthResource;
use App\Mail\OrganizationInvite as MailOrganizationInvite;
use App\Models\Organization;
use App\Models\OrganizationInvite;
use App\Models\User;
use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class OrganizationController extends Controller
{
    public function __construct(private readonly OrganizationService $organizationService)
    {}
    public function index(IndexOrganizationRequest $request): IndexOrganizationResource
    {
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50) ? $request->limit : 5;
        $organizations = Organization::query();
        $response =
            app(Pipeline::class)
            ->send($organizations)
            ->through([
                OrganizationName::class,
                OrganizationDescription::class,
                OrganizationUser::class,
                OrganizationLocationFilter::class,
            ])
            ->via("apply")
            ->then(function ($organizations) use($page, $limit) {
                return $organizations->orderBy('created_at', 'desc')->cursorPaginate($limit, ['*'], 'page' , $page);
            });

        return new IndexOrganizationResource($response);
    }
    public function show($id)
    {
        $organization = Organization::find($id);
        return new ShowOrganizationSuccessResource($organization);
    }

    public function store(CreateOrganizationRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();
        if (!isset($user)) {
            return new StoreOrganizationNoAuthResource([]);
        }
        $data['user_id'] = $user->id;
        $organization = $this->organizationService->store($data);

        return new StoreOrganizationSuccessResource($organization);
    }

    public function getUsersOfOrganization($organizationId)
    {
        $organizationWithUsers = Organization::where("id", $organizationId)->with("users")->get();

        return response()->json([["organizations" => ["data" => $organizationWithUsers], "message" => "success"], 200]);
    }



    public function addUserToOrganization($organizationId, $userId, Request $request)
    {
        $invitedUser = User::find($userId);
        $authUser = auth("api")->user();
        $authUserPermissions = $authUser->permissionsInOrganization()->where("organization_id", $organizationId)->get();
        $organization = Organization::find($organizationId);

        if (!$invitedUser) {
            return response()->json(["message" => "User is not found"], 404);
        }
        if (!$organization) {
            return response()->json(["message" => "Organization is not found"], 404);
        }

        $res = $organization->users()->where("user_id", $invitedUser->id)->exists();

        if ($res) {
            return response()->json(["message" => "User alredy in organization"], 409);
        }

        $invitedUserEmail = $invitedUser->email;
        $permissions = $request->get("permissions");
        do {
            $token = Str::random(40);
            $token = bcrypt($token);
            $url = URL::temporarySignedRoute("organizationInvite.accept", now()->addDays(3), ["token" => $token]);
        } while (OrganizationInvite::where("url", $token)->first());

        $invite = OrganizationInvite::create([
            "email" => $invitedUserEmail,
            "url" => $url,
            "user_id" => $userId,
            "organization_id" => $organizationId,
            "token" => $token,
        ]);

        foreach ($permissions as $permission) {
            $invite->userPermissions()->attach($permission, [
                "user_id" => $userId,
                "organization_invite_id" => $invite->id
            ]);
        }

        // Обернуть в try catch
        try {
            Mail::to($invitedUserEmail)->send(new MailOrganizationInvite($invite));
        } catch (Exception $e) {
            Log::error("Произошла ошибка при отправке приглашения " . $e);
            return response()->json(["message" => "failed to send an invitation"]);
        }


        return response()->json(["message" => "success"], 200);
    }

    public function addOrDeletePermissionToUser($organizationId, $userId, $permId)
    {
        $user = User::find($userId);

        $organization = Organization::find($organizationId);

        if (!$user) {
            return response()->json(["message" => "User is not found"], 404);
        }
        if (!$organization) {
            return response()->json(["message" => "Organization is not found"], 404);
        }

        $user->permissionsInOrganization()->where("organization_id", $organizationId)->toggle([$permId => ["organization_id" => $organizationId]]);

        return response()->json(["message" => "permission was changed"], 200);
    }

    public function getPermissionsOfUser($organizationId, $userId)
    {
        $user = User::find($userId);
        $permissions = $user->permissionsInOrganization()->where("organization_id", $organizationId)->get();
        return response()->json(["data" => ["permissions" => $permissions]]);
    }

    public function getEvents(Request $request, $organizationId) {
        $events = $this->organizationService->getEvents($organizationId, $request);

        return response()->json(["events" => $events]);
    }
    public function delete($id): SuccessDeleteOrganizationResource | FailedDeleteOrganizationResource
    {
        $org = $this->organizationService->delete($id);
        if ($org){
            return new SuccessDeleteOrganizationResource([]);
        } else {
            return new FailedDeleteOrganizationResource([]);
        }
    }

    public function organizationTransferUser(int $org_id, int $user_id) {
        try {
            $this->organizationService->organizationTransferUser($org_id, $user_id);
            return new SuccessOrganizationTransferUserResource([]);
        } catch (Exception $e) {
            return match ($e->getCode()) {
                'You are not allowed to transfer users to this organization' => new NotFoundOrganizationTransferUserResource([]),
                default => new NotPermissionOrganizationTransferUserResource([]),
            };
        }
    }
}
