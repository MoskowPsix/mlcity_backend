<?php

namespace App\Services\MototrackDevice;

use App\Models\MototrackDevicePlaceMapping;
use Illuminate\Support\Facades\Log;

class RfidEventProcessor
{
    public function __construct(
        private readonly MototrackRiderRunProcessor $riderRunProcessor,
    ) {}

    public function process(array $event): bool
    {
        $devicePlace = $this->resolveDevicePlace($event);

        if ($devicePlace === null) {
            Log::warning('Mototrack RFID event skipped: device is not mapped to place', [
                'event_id' => $event['event_id'] ?? null,
                'device_id' => $event['device_id'] ?? null,
            ]);

            return false;
        }

        return $this->riderRunProcessor->process($event, $devicePlace);
    }

    private function resolveDevicePlace(array $event): ?MototrackDevicePlaceMapping
    {
        $deviceId = trim((string) ($event['device_id'] ?? ''));

        if ($deviceId === '') {
            return null;
        }

        return MototrackDevicePlaceMapping::query()
            ->where('device_id', $deviceId)
            ->first();
    }
}
