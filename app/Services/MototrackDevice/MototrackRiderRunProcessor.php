<?php

namespace App\Services\MototrackDevice;

use App\Models\MototrackDevicePlaceMapping;
use App\Models\MototrackRiderRun;
use App\Models\RfidTagMapping;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

class MototrackRiderRunProcessor
{
    public function process(array $event, MototrackDevicePlaceMapping $deviceSight): bool
    {
        $motoTagNumber = $this->resolveMotoTagNumber($event);
        $user = $this->resolveUser($event, $motoTagNumber);
        $eventId = $this->eventId($event);

        if ($user === null) {
            Log::warning('Mototrack rider run skipped: user not resolved', [
                'event_id' => $event['event_id'] ?? null,
                'device_id' => $event['device_id'] ?? null,
                'moto_tag_number' => $motoTagNumber,
            ]);

            return false;
        }

        if ($eventId !== null && $this->eventAlreadyProcessed($eventId)) {
            return false;
        }

        $eventAt = $this->eventTime($event);
        $openRun = MototrackRiderRun::query()
            ->where('user_id', $user->id)
            ->where('sight_id', $deviceSight->sight_id)
            ->whereNull('finished_at')
            ->orderByDesc('started_at')
            ->first();

        if ($openRun === null) {
            MototrackRiderRun::query()->create([
                'user_id' => $user->id,
                'sight_id' => $deviceSight->sight_id,
                'device_id' => (string) ($event['device_id'] ?? ''),
                'rfid_tag_number' => $motoTagNumber,
                'start_event_id' => $eventId,
                'started_at' => $eventAt,
                'start_payload' => $this->eventSnapshot($event),
            ]);

            return true;
        }

        $durationMs = max(0, $eventAt->getTimestampMs() - $openRun->started_at->getTimestampMs());

        if ($durationMs < $this->minRunDurationMs()) {
            Log::info('Mototrack rider run finish skipped: duplicate pass interval', [
                'run_id' => $openRun->id,
                'user_id' => $user->id,
                'sight_id' => $deviceSight->sight_id,
                'duration_ms' => $durationMs,
            ]);

            return false;
        }

        $openRun->update([
            'finish_event_id' => $eventId,
            'finished_at' => $eventAt,
            'duration_ms' => $durationMs,
            'finish_payload' => $this->eventSnapshot($event),
        ]);

        MototrackRiderRun::query()->create([
            'user_id' => $user->id,
            'sight_id' => $deviceSight->sight_id,
            'device_id' => (string) ($event['device_id'] ?? ''),
            'rfid_tag_number' => $motoTagNumber,
            'start_event_id' => null,
            'started_at' => $eventAt,
            'start_payload' => $this->eventSnapshot($event),
        ]);

        return true;
    }

    private function resolveUser(array $event, ?string $motoTagNumber): ?User
    {
        $candidates = $this->candidateTagNumbers($event, $motoTagNumber);

        if ($candidates === []) {
            return null;
        }

        $userId = null;
        if ($motoTagNumber !== null && $motoTagNumber !== '') {
            $userId = User::query()
                ->where('rfid_tag_number', (string) $motoTagNumber)
                ->value('id');
        }

        if ($userId === null) {
            $userId = User::query()
                ->whereIn('rfid_tag_number', $candidates)
                ->value('id');
        }

        if ($userId === null) {
            Log::warning('Mototrack rider run skipped: user not found for tag candidates', [
                'event_id' => $event['event_id'] ?? null,
                'device_id' => $event['device_id'] ?? null,
                'moto_tag_number' => $motoTagNumber,
                'candidates' => $candidates,
            ]);

            return null;
        }

        return User::query()->find((int) $userId);
    }

    private function eventAlreadyProcessed(int $eventId): bool
    {
        return MototrackRiderRun::query()
            ->where(function ($query) use ($eventId): void {
                $query
                    ->where('start_event_id', $eventId)
                    ->orWhere('finish_event_id', $eventId);
            })
            ->exists();
    }

    private function candidateTagNumbers(array $event, ?string $motoTagNumber): array
    {
        $values = collect();

        if ($motoTagNumber !== null && trim((string) $motoTagNumber) !== '') {
            $values->push(trim((string) $motoTagNumber));
        }

        foreach ($this->sensorValues($event) as $value) {
            $raw = trim((string) $value);
            if ($raw !== '') {
                $values->push($raw);
            }

            $normalized = RfidTagMapping::normalizeSensorValue($value);
            if ($normalized === '') {
                continue;
            }

            $values->push($normalized);

            if (preg_match('/^[0-9A-F]+$/i', $normalized) === 1) {
                $stripped = ltrim($normalized, '0');
                $values->push($stripped === '' ? '0' : $stripped);
            }
        }

        return $values
            ->filter(fn (mixed $value): bool => is_string($value) && $value !== '')
            ->unique()
            ->values()
            ->all();
    }

    private function resolveMotoTagNumber(array $event): ?string
    {
        $sensorValues = $this->sensorValues($event);

        if ($sensorValues === []) {
            return null;
        }

        $normalizedValues = collect($sensorValues)
            ->map(fn (mixed $value): string => RfidTagMapping::normalizeSensorValue($value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($normalizedValues === []) {
            return null;
        }

        return RfidTagMapping::query()
            ->whereIn('normalized_sensor_value', $normalizedValues)
            ->value('moto_tag_number');
    }

    private function sensorValues(array $event): array
    {
        $payload = is_array($event['payload'] ?? null) ? $event['payload'] : [];

        return array_values(array_filter([
            $event['epc'] ?? null,
            $payload['epc'] ?? null,
            $payload['sensor_value'] ?? null,
            $payload['tag'] ?? null,
            $payload['raw_tag'] ?? null,
            $payload['uid'] ?? null,
        ], fn ($value): bool => is_scalar($value) && trim((string) $value) !== ''));
    }

    private function eventTime(array $event): CarbonImmutable
    {
        $utcUs = $event['utc_us'] ?? null;

        if (is_numeric($utcUs) && (int) $utcUs > 0) {
            return CarbonImmutable::createFromTimestampMsUTC(intdiv((int) $utcUs, 1000));
        }

        $utcUnix = $event['utc_unix'] ?? null;

        if (is_numeric($utcUnix) && (int) $utcUnix > 0) {
            return CarbonImmutable::createFromTimestampUTC((int) $utcUnix);
        }

        $receivedAt = $event['received_at'] ?? null;

        if (is_string($receivedAt) && $receivedAt !== '') {
            return CarbonImmutable::parse($receivedAt)->utc();
        }

        return CarbonImmutable::now('UTC');
    }

    private function eventId(array $event): ?int
    {
        $eventId = $event['event_id'] ?? null;

        return is_numeric($eventId) ? (int) $eventId : null;
    }

    private function eventSnapshot(array $event): array
    {
        return [
            'event_id' => $event['event_id'] ?? null,
            'device_id' => $event['device_id'] ?? null,
            'epc' => $event['epc'] ?? null,
            'utc_us' => $event['utc_us'] ?? null,
            'utc_unix' => $event['utc_unix'] ?? null,
            'received_at' => $event['received_at'] ?? null,
            'payload' => $event['payload'] ?? null,
        ];
    }

    private function minRunDurationMs(): int
    {
        return (int) config('services.mototrack_device_service.min_run_duration_ms', 5000);
    }
}
