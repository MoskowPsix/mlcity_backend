<?php

namespace App\Console\Commands;

use App\Services\MototrackDevice\MototrackDeviceServiceClient;
use App\Services\MototrackDevice\RfidEventProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncMototrackRfidEventsCommand extends Command
{
    protected $signature = 'mototrack:rfid-sync
        {--once : Выполнить одну итерацию и завершиться}
        {--from_id= : Начать чтение с конкретного event_id}
        {--sleep=5 : Пауза между итерациями в секундах, когда новых событий нет}';

    protected $description = 'Получает RFID-метки из mototrack-device-service и пишет круги хронометража';

    public function handle(MototrackDeviceServiceClient $client, RfidEventProcessor $processor): int
    {
        $once = (bool) $this->option('once');
        $lastEventIdKey = (string) config('services.mototrack_device_service.last_event_id_key');
        $fromId = $this->option('from_id') !== null
            ? (int) $this->option('from_id')
            : (int) (Cache::get($lastEventIdKey) ?: 0);
        $limit = max(1, min(1000, (int) config('services.mototrack_device_service.poll_limit', 100)));
        $sleep = max(1, (int) $this->option('sleep'));

        $this->info(sprintf(
            'RFID sync started from_id=%d url=%s',
            $fromId,
            rtrim((string) config('services.mototrack_device_service.base_url'), '/')
        ));

        do {
            try {
                $response = $client->rfidEvents($fromId, $limit);
                $events = is_array($response['events'] ?? null) ? $response['events'] : [];

                foreach ($events as $event) {
                    if (! is_array($event)) {
                        continue;
                    }

                    $eventId = (int) ($event['event_id'] ?? 0);

                    try {
                        if ($processor->process($event)) {
                            $this->line("Processed RFID event {$eventId}");
                        }
                    } catch (Throwable $e) {
                        Log::error('Mototrack RFID event processing failed', [
                            'event_id' => $eventId,
                            'from_id' => $fromId,
                            'message' => $e->getMessage(),
                        ]);
                        $this->error("Failed RFID event {$eventId}: {$e->getMessage()}");
                    }

                    if ($eventId > $fromId) {
                        $fromId = $eventId;
                        Cache::forever($lastEventIdKey, $fromId);
                    }
                }

                $hadEvents = $events !== [];
                unset($response, $events);

                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

                if ($once) {
                    break;
                }

                sleep($hadEvents ? 1 : $sleep);
            } catch (Throwable $e) {
                Log::error('Mototrack RFID sync poll failed', [
                    'from_id' => $fromId,
                    'message' => $e->getMessage(),
                ]);
                $this->error("RFID poll failed (from_id={$fromId}): {$e->getMessage()}");

                if ($once) {
                    return self::FAILURE;
                }

                sleep($sleep);
            }
        } while (true);

        return self::SUCCESS;
    }
}
