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
use App\Models\Organization;
use Illuminate\Pipeline\Pipeline;

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

            return response()->json(["data"=>["organization"=>$response]],200);
    }
    public function show($id) {
        $organization = Organization::find($id);

        return response()->json(["data"=>["organization"=>$organization]],200);
    }

    public function store(CreateOrganisation $request) {
        $data = $request->validated();
        info($data);
        $organization = Organization::create($data);

        return response()->json(["organization"=>$organization, "message"=>"success"], $status=201);
    }
    public function delete($id) {

    }
}
