<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\PointService\PointService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\Point\StorePointsRequest;
use App\Http\Resources\Point\Delete\NotFoundDeletePointResource;
use App\Http\Resources\Point\Delete\SuccessDeletePointResource;
use App\Http\Resources\Point\GetPoint\SuccessGetPointsResource;
use App\Http\Resources\Point\StorePoint\SuccessStorePointsResource;
use Exception;

class PointController extends Controller
{
    public function __construct(private readonly PointService $pointService)
    {}

    public function store(StorePointsRequest $request): SuccessStorePointsResource
    {
        $point = $this->pointService->store($request);
        return new SuccessStorePointsResource($point);
    }
    public function getForUser(PageANDLimitRequest $request): SuccessGetPointsResource
    {
        $response = $this->pointService->getForUser($request);
        return new SuccessGetPointsResource($response);
    }
    public function delete(int $id): SuccessDeletePointResource | NotFoundDeletePointResource
    {
        try {
            $this->pointService->delete($id);
            return new SuccessDeletePointResource([]);
        } catch (Exception $e) {
            return new NotFoundDeletePointResource([]);
        }
    }
}
