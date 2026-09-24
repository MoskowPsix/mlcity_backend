<?php

namespace App\Services\MototrackDevice;

use Illuminate\Support\Facades\Http;

class MototrackDeviceServiceClient
{
    public function rfidEvents(int $fromId, int $limit): array
    {
        $baseUrl = rtrim((string) config('services.mototrack_device_service.base_url'), '/');
        $token = (string) config('services.mototrack_device_service.token', '');

        $request = Http::timeout((int) config('services.mototrack_device_service.timeout', 5))
            ->acceptJson();

        if ($token !== '') {
            $request = $request->withToken($token);
        }

        return $request
            ->get($baseUrl.'/api/v1/rfid-events', [
                'from_id' => $fromId,
                'limit' => $limit,
            ])
            ->throw()
            ->json();
    }
}
