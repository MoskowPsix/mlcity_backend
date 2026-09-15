<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\Integration\CartingIntegrationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Integration\CartingCreateEventRequest;
use App\Http\Requests\Integration\CartingCreateSightRequest;
use App\Http\Resources\Event\EventResource;
use App\Http\Resources\Sight\SightResource;
use Exception;
use Illuminate\Http\JsonResponse;

class CartingIntegrationController extends Controller
{
    public function __construct(
        private readonly CartingIntegrationService $service
    ) {}

    public function createEvent(CartingCreateEventRequest $request): JsonResponse|EventResource
    {
        try {
            $event = $this->service->createOrGetEvent($request);

            return (new EventResource($event))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось создать мероприятие из картинга',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createSight(CartingCreateSightRequest $request): JsonResponse|SightResource
    {
        try {
            $sight = $this->service->createOrGetSight($request);

            return (new SightResource($sight))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось создать локацию из картинга',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteEvent(string $sourceId): JsonResponse
    {
        try {
            $deleted = $this->service->deleteEvent($sourceId);

            return response()->json([
                'message' => $deleted ? 'Мероприятие удалено' : 'Мероприятие не найдено',
                'deleted' => $deleted,
            ], $deleted ? 200 : 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось удалить мероприятие из картинга',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteSight(string $sourceId): JsonResponse
    {
        try {
            $deleted = $this->service->deleteSight($sourceId);

            return response()->json([
                'message' => $deleted ? 'Локация удалена' : 'Локация не найдена',
                'deleted' => $deleted,
            ], $deleted ? 200 : 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось удалить локацию из картинга',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
