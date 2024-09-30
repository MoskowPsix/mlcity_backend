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
use App\Models\Point;
use Exception;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group(name: 'UserPoint', description: 'Сохранённое местоположение пользователя')]
class PointController extends Controller
{
    public function __construct(private readonly PointService $pointService)
    {}
    #[Authenticated]
    #[ResponseFromApiResource(SuccessStorePointsResource::class, Point::class)]
    #[Endpoint(title: 'create', description: 'Создание пользовательской точки')]
    public function store(StorePointsRequest $request): SuccessStorePointsResource
    {
        $point = $this->pointService->store($request);
        return new SuccessStorePointsResource($point);
    }
    #[Authenticated]
    #[ResponseFromApiResource(SuccessGetPointsResource::class, Point::class)]
    #[Endpoint(title: 'getForUser', description: 'Получение точек пользователя')]
    public function getForUser(PageANDLimitRequest $request): SuccessGetPointsResource
    {
        $response = $this->pointService->getForUser($request);
        return new SuccessGetPointsResource($response);
    }
    #[Authenticated]
    #[UrlParam('id', 'integer', description: 'id точки', required: true, example: 1)]
    #[ResponseFromApiResource(SuccessDeletePointResource::class, null)]
    #[ResponseFromApiResource(NotFoundDeletePointResource::class, null, 400)]
    #[Endpoint(title: 'delete', description: 'Удаление точки пользователя')]
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
