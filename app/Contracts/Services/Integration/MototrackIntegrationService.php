<?php

namespace App\Contracts\Services\Integration;

use App\Constants\MototrackSourceConstants;
use App\Constants\StatusesConstants;
use App\Http\Requests\Integration\MototrackCreateEventRequest;
use App\Http\Requests\Integration\MototrackCreateSightRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Sight;
use App\Models\SightType;
use App\Models\Status;
use App\Models\Timezone;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MototrackIntegrationService
{
    public function createOrGetEvent(MototrackCreateEventRequest $request): Event
    {
        $existing = Event::query()
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->where('source_id', (string) $request->sourceId)
            ->first();

        if ($existing) {
            return $this->updateEvent($existing, $request);
        }

        DB::beginTransaction();
        try {
            $userId = (int) config('services.mototrack.user_id', 1);
            $location = $this->resolveLocation((float) $request->latitude, (float) $request->longitude);
            $timezoneId = $this->resolveTimezoneId($location);
            $sight = $this->resolveSightForEvent($request, $location);
            if (!$sight) {
                $sight = $this->createFallbackSight($request, $location, $userId);
            }
            $typeId = $this->resolveMotorsportEventTypeId();
            $publishedStatusId = $this->publishedStatusId();

            $organization = $sight->organization()->first();
            if (!$organization) {
                $organization = $sight->organization()->create();
            }

            $event = Event::create([
                'name' => $request->name,
                'sponsor' => MototrackSourceConstants::SPONSOR,
                'description' => $request->description ?? '',
                'date_start' => $request->dateStart,
                'date_end' => $request->dateEnd,
                'user_id' => $userId,
                'organization_id' => $organization->id,
                'source_id' => (string) $request->sourceId,
                'source_name' => MototrackSourceConstants::SOURCE_NAME,
                'materials' => $request->input('materials'),
            ]);

            $place = $event->places()->create([
                'sight_id' => $sight?->id,
                'location_id' => $location->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address ?: ($sight?->address ?: $location->name),
                'timezone_id' => $timezoneId,
            ]);

            $place->seances()->create([
                'date_start' => $request->dateStart,
                'date_end' => $request->dateEnd,
            ]);

            $event->types()->sync([$typeId]);
            // Правило для контента с мототрека: сразу «Опубликовано», без модерации
            $event->statuses()->attach($publishedStatusId, ['last' => true]);
            $event->likes()->create();
            $this->syncEventImages($event, $request->input('images'));
            $this->syncEventPrices($event, $request->input('prices'));

            DB::commit();

            return $event->load(['types', 'statuses', 'places', 'files', 'price']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MototrackIntegrationService::createOrGetEvent failed', [
                'source_id' => $request->sourceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateEvent(Event $event, MototrackCreateEventRequest $request): Event
    {
        DB::beginTransaction();
        try {
            $location = $this->resolveLocation((float) $request->latitude, (float) $request->longitude);
            $timezoneId = $this->resolveTimezoneId($location);
            $sight = $this->resolveSightForEvent($request, $location);

            $event->update([
                'name' => $request->name,
                'description' => $request->description ?? '',
                'date_start' => $request->dateStart,
                'date_end' => $request->dateEnd,
                'materials' => $request->input('materials', $event->materials),
            ]);

            $place = $event->places()->first();
            $linkedSightId = $sight?->id ?? $place?->sight_id;
            if ($place) {
                $place->update([
                    'sight_id' => $linkedSightId,
                    'location_id' => $location->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'address' => $request->address ?: ($sight?->address ?: $location->name),
                    'timezone_id' => $timezoneId,
                ]);

                $seance = $place->seances()->first();
                if ($seance) {
                    $seance->update([
                        'date_start' => $request->dateStart,
                        'date_end' => $request->dateEnd,
                    ]);
                } else {
                    $place->seances()->create([
                        'date_start' => $request->dateStart,
                        'date_end' => $request->dateEnd,
                    ]);
                }
            } else {
                $place = $event->places()->create([
                    'sight_id' => $linkedSightId,
                    'location_id' => $location->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'address' => $request->address ?: ($sight?->address ?: $location->name),
                    'timezone_id' => $timezoneId,
                ]);
                $place->seances()->create([
                    'date_start' => $request->dateStart,
                    'date_end' => $request->dateEnd,
                ]);
            }

            // Карта «Места» читает coords у Sight, не у Place — обновляем связанный мото-sight
            $this->syncLinkedMototrackSightCoords(
                $linkedSightId,
                $request,
                $location,
            );

            $this->ensurePublishedStatus($event);
            $this->syncEventImages($event, $request->input('images'));
            $this->syncEventPrices($event, $request->input('prices'));

            DB::commit();

            return $event->load(['types', 'statuses', 'places', 'files', 'price']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MototrackIntegrationService::updateEvent failed', [
                'source_id' => $request->sourceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteEvent(string $sourceId): bool
    {
        $event = Event::query()
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->where('source_id', $sourceId)
            ->first();

        if (!$event) {
            return false;
        }

        $event->delete();

        return true;
    }

    public function createOrGetSight(MototrackCreateSightRequest $request): Sight
    {
        $existing = Sight::query()
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->where('source_id', (string) $request->sourceId)
            ->first();

        if ($existing) {
            return $this->updateSight($existing, $request);
        }

        DB::beginTransaction();
        try {
            $userId = (int) config('services.mototrack.user_id', 1);
            $location = $this->resolveLocation((float) $request->latitude, (float) $request->longitude);
            $typeId = $this->resolveMotorsportSightTypeId();
            $publishedStatusId = $this->publishedStatusId();

            $sight = Sight::create([
                'name' => $request->name,
                'sponsor' => MototrackSourceConstants::SPONSOR,
                'location_id' => $location->id,
                'address' => $request->address ?: $location->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'description' => $request->description ?? '',
                'user_id' => $userId,
                'source_id' => (string) $request->sourceId,
                'source_name' => MototrackSourceConstants::SOURCE_NAME,
            ]);

            $sight->organization()->create();
            $sight->types()->sync([$typeId]);
            // Правило для контента с мототрека: сразу «Опубликовано», без модерации
            $sight->statuses()->attach($publishedStatusId, ['last' => true]);
            $sight->likes()->create();
            $this->syncSightImages($sight, $request->input('images'));

            DB::commit();

            return $sight->load(['types', 'statuses', 'locations', 'files']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MototrackIntegrationService::createOrGetSight failed', [
                'source_id' => $request->sourceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateSight(Sight $sight, MototrackCreateSightRequest $request): Sight
    {
        DB::beginTransaction();
        try {
            $location = $this->resolveLocation((float) $request->latitude, (float) $request->longitude);

            $sight->update([
                'name' => $request->name,
                'location_id' => $location->id,
                'address' => $request->address ?: $location->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'description' => $request->description ?? '',
            ]);

            $this->ensurePublishedStatus($sight);
            $this->syncSightImages($sight, $request->input('images'));

            DB::commit();

            return $sight->load(['types', 'statuses', 'locations', 'files']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('MototrackIntegrationService::updateSight failed', [
                'source_id' => $request->sourceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Пишет URL картинок мототрека в sight_files (local=0, без копирования файла).
     *
     * @param  list<string>|null  $images
     */
    private function syncSightImages(Sight $sight, ?array $images): void
    {
        $this->syncExternalImages($sight, $images, 'sight');
    }

    /**
     * Пишет URL картинок мототрека в event_files (local=0, без копирования файла).
     *
     * @param  list<string>|null  $images
     */
    private function syncEventImages(Event $event, ?array $images): void
    {
        $this->syncExternalImages($event, $images, 'event');
    }

    /**
     * Синхронизация цен по платным классам мототрека.
     *
     * @param  list<array{cost_rub?: int|string, descriptions?: string}>|null  $prices
     */
    private function syncEventPrices(Event $event, ?array $prices): void
    {
        if ($prices === null) {
            return;
        }

        $event->price()->delete();

        foreach ($prices as $price) {
            $cost = (int) ($price['cost_rub'] ?? 0);
            if ($cost <= 0) {
                continue;
            }

            $event->price()->create([
                'cost_rub' => $cost,
                'descriptions' => (string) ($price['descriptions'] ?? ''),
            ]);
        }
    }

    /**
     * @param  Sight|Event  $model
     * @param  list<string>|null  $images
     */
    private function syncExternalImages($model, ?array $images, string $context): void
    {
        if ($images === null) {
            return;
        }

        $typeId = FileType::query()->where('name', 'image')->value('id');
        if (!$typeId) {
            Log::warning("MototrackIntegrationService::syncExternalImages: FileType image not found", [
                'context' => $context,
                'id' => $model->id,
            ]);

            return;
        }

        $model->files()->delete();

        foreach ($images as $url) {
            if (!is_string($url) || $url === '') {
                continue;
            }

            $model->files()->create([
                'name' => uniqid('img_'),
                'link' => $url,
                'local' => 0,
            ])->file_types()->attach($typeId);
        }
    }

    public function deleteSight(string $sourceId): bool
    {
        $sight = Sight::query()
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->where('source_id', $sourceId)
            ->first();

        if (!$sight) {
            return false;
        }

        $sight->delete();

        return true;
    }

    private function resolveSightForEvent(MototrackCreateEventRequest $request, Location $location): ?Sight
    {
        if ($request->filled('trackSourceId')) {
            $sight = Sight::query()
                ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
                ->where('source_id', (string) $request->trackSourceId)
                ->first();

            if ($sight) {
                return $sight;
            }
        }

        // Fallback-sight, созданный из гонки (source_id = event:{raceId})
        $fallback = Sight::query()
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->where('source_id', 'event:' . $request->sourceId)
            ->first();
        if ($fallback) {
            return $fallback;
        }

        return Sight::query()
            ->where('latitude', $request->latitude)
            ->where('longitude', $request->longitude)
            ->first();
    }

    private function syncLinkedMototrackSightCoords(
        ?int $sightId,
        MototrackCreateEventRequest $request,
        Location $location,
    ): void {
        if (!$sightId) {
            return;
        }

        $sight = Sight::query()
            ->where('id', $sightId)
            ->where('source_name', MototrackSourceConstants::SOURCE_NAME)
            ->first();

        if (!$sight) {
            return;
        }

        $sight->update([
            'location_id' => $location->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address ?: ($sight->address ?: $location->name),
        ]);
    }

    private function createFallbackSight(
        MototrackCreateEventRequest $request,
        Location $location,
        int $userId
    ): Sight {
        $typeId = $this->resolveMotorsportSightTypeId();
        $publishedStatusId = $this->publishedStatusId();

        $sight = Sight::create([
            'name' => $request->name,
            'sponsor' => MototrackSourceConstants::SPONSOR,
            'location_id' => $location->id,
            'address' => $request->address ?: $location->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description ?? '',
            'user_id' => $userId,
            'source_id' => 'event:' . $request->sourceId,
            'source_name' => MototrackSourceConstants::SOURCE_NAME,
        ]);

        $sight->organization()->create();
        $sight->types()->sync([$typeId]);
        $sight->statuses()->attach($publishedStatusId, ['last' => true]);
        $sight->likes()->create();

        return $sight;
    }

    private function resolveLocation(float $latitude, float $longitude): Location
    {
        $radius = 5;
        $location = null;

        while ($location === null && $radius <= 500) {
            $location = Location::query()
                ->whereRaw(
                    '(
                    6371 *
                    acos(cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude)))
                ) <= ?',
                    [$latitude, $longitude, $latitude, $radius]
                )
                ->orderByRaw(
                    '(
                    6371 *
                    acos(cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude)))
                ) ASC',
                    [$latitude, $longitude, $latitude]
                )
                ->first();

            $radius += 5;
        }

        if (!$location) {
            $location = Location::query()->orderBy('id')->first();
        }

        if (!$location) {
            throw new Exception('Не найден город (location) для координат мототрека');
        }

        return $location;
    }

    private function resolveTimezoneId(Location $location): ?int
    {
        if (empty($location->time_zone)) {
            return Timezone::query()->orderBy('id')->value('id');
        }

        return Timezone::query()->where('name', $location->time_zone)->value('id')
            ?? Timezone::query()->orderBy('id')->value('id');
    }

    private function publishedStatusId(): int
    {
        $status = Status::query()->where('name', StatusesConstants::PUBLISH)->first();
        if (!$status) {
            throw new Exception('Статус «Опубликовано» не найден');
        }

        return (int) $status->id;
    }

    private function ensurePublishedStatus(Event|Sight $entity): void
    {
        $publishedStatusId = $this->publishedStatusId();
        // reorder(): у связи statuses() есть orderBy(pivot_created_at), который ломает exists()
        $lastPublished = $entity->statuses()
            ->reorder()
            ->where('statuses.id', $publishedStatusId)
            ->wherePivot('last', true)
            ->exists();

        if ($lastPublished) {
            return;
        }

        foreach ($entity->statuses as $status) {
            $entity->statuses()->updateExistingPivot($status->id, ['last' => false]);
        }

        $entity->statuses()->attach($publishedStatusId, ['last' => true]);
    }

    private function resolveMotorsportEventTypeId(): int
    {
        $type = EventType::query()->where('name', MototrackSourceConstants::TYPE_NAME)->first();
        if ($type) {
            return (int) $type->id;
        }

        $type = EventType::create([
            'name' => MototrackSourceConstants::TYPE_NAME,
            'ico' => '/storage/icons/ball.svg',
            'order' => 10,
        ]);

        return (int) $type->id;
    }

    private function resolveMotorsportSightTypeId(): int
    {
        $type = SightType::query()->where('name', MototrackSourceConstants::TYPE_NAME)->first();
        if ($type) {
            return (int) $type->id;
        }

        $type = SightType::create([
            'name' => MototrackSourceConstants::TYPE_NAME,
            'ico' => '/storage/icons/ball.svg',
            'order' => 10,
        ]);

        return (int) $type->id;
    }
}
