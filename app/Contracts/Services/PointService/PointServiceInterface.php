<?php

namespace App\Contracts\Services\PointService;

use App\Http\Requests\PageANDLimitRequest;
use App\Http\Requests\Point\GetPointsRequest;
use App\Http\Requests\Point\StorePointsRequest;

interface PointServiceInterface
{
    public function store(StorePointsRequest $request): object;
    public function getForUser(PageANDLimitRequest $request): object;
    public function delete(int $id): object;
}
