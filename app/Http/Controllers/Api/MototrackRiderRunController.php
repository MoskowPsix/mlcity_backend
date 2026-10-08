<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mototrack\MototrackRiderRunResource;
use App\Models\MototrackRiderRun;
use App\Models\Sight;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MototrackRiderRunController extends Controller
{
    private const FALLBACK_TIMEZONE = 'Asia/Yekaterinburg';

    public function index(Request $request): JsonResponse
    {
        $userId = auth('api')->id();

        if ($userId === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        $runs = MototrackRiderRun::query()
            ->with(['sight:id,name,location_id', 'sight.locations:id,name,time_zone,time_zone_utc'])
            ->where('user_id', $userId)
            ->orderByDesc('started_at')
            ->orderByDesc('id')
            ->get();

        $places = $runs
            ->groupBy('sight_id')
            ->map(function ($sightRuns) {
                $sortedRuns = $sightRuns
                    ->sortByDesc(fn (MototrackRiderRun $run) => [$run->started_at?->timestamp ?? 0, $run->id])
                    ->values();
                $firstRun = $sortedRuns->first();

                return [
                    'sight_id' => $firstRun->sight_id,
                    'sight_name' => $this->sightDisplayName($firstRun),
                    'place_id' => $firstRun->sight_id,
                    'place_name' => $this->sightDisplayName($firstRun),
                    'timezone' => $this->timezoneForRun($firstRun),
                    'runs' => MototrackRiderRunResource::collection($sortedRuns)->resolve(),
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'sessions' => $this->buildSessions($runs),
            'places' => $places,
            'runs' => MototrackRiderRunResource::collection($runs)->resolve(),
        ]);
    }

    public function destroySession(Request $request): JsonResponse
    {
        $userId = auth('api')->id();

        if ($userId === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'sight_id' => ['required', 'integer', 'min:1'],
        ]);

        $sight = Sight::query()
            ->with('locations:id,name,time_zone,time_zone_utc')
            ->find((int) $validated['sight_id']);

        $timezone = $this->resolveTimezone($sight?->locations?->time_zone);
        $dayStart = CarbonImmutable::createFromFormat('Y-m-d', $validated['date'], $timezone)
            ->startOfDay()
            ->utc();
        $dayEnd = CarbonImmutable::createFromFormat('Y-m-d', $validated['date'], $timezone)
            ->endOfDay()
            ->utc();

        $deleted = MototrackRiderRun::query()
            ->where('user_id', $userId)
            ->where('sight_id', (int) $validated['sight_id'])
            ->whereBetween('started_at', [$dayStart, $dayEnd])
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => $deleted > 0 ? 'Сессия удалена' : 'Сессия не найдена',
            'deleted' => $deleted,
        ]);
    }

    /**
     * @param  Collection<int, MototrackRiderRun>  $runs
     * @return list<array<string, mixed>>
     */
    private function buildSessions(Collection $runs): array
    {
        $grouped = $runs
            ->filter(static fn (MototrackRiderRun $run): bool => $run->started_at !== null)
            ->groupBy(function (MototrackRiderRun $run): string {
                $timezone = $this->timezoneForRun($run);
                $date = CarbonImmutable::parse($run->started_at)->timezone($timezone)->format('Y-m-d');

                return $date.'#'.(int) $run->sight_id;
            });

        return $grouped
            ->map(function (Collection $sessionRuns): array {
                $sorted = $sessionRuns
                    ->sortBy(fn (MototrackRiderRun $run) => [$run->started_at?->timestamp ?? 0, $run->id])
                    ->values();

                /** @var MototrackRiderRun $first */
                $first = $sorted->first();
                $timezone = $this->timezoneForRun($first);
                $localStart = CarbonImmutable::parse($first->started_at)->timezone($timezone);
                $dateKey = $localStart->format('Y-m-d');
                $dateLabel = $localStart->format('d.m.Y');
                $sightName = $this->sightDisplayName($first);

                $laps = [[
                    'type' => 'start',
                    'label' => 'Старт',
                    'lap_number' => null,
                    'time' => $this->formatClock($localStart),
                    'at' => $first->started_at?->toISOString(),
                    'duration_ms' => null,
                    'run_id' => null,
                    'status' => null,
                ]];

                $lapNumber = 0;
                foreach ($sorted as $run) {
                    if ($run->finished_at === null || $run->duration_ms === null) {
                        continue;
                    }

                    $lapNumber++;
                    $laps[] = [
                        'type' => 'lap',
                        'label' => 'Круг '.$lapNumber,
                        'lap_number' => $lapNumber,
                        'time' => $this->formatDuration((int) $run->duration_ms),
                        'at' => $run->finished_at?->toISOString(),
                        'duration_ms' => (int) $run->duration_ms,
                        'run_id' => $run->id,
                        'status' => 'finished',
                    ];
                }

                return [
                    'key' => $dateKey.'#'.(int) $first->sight_id,
                    'date' => $dateKey,
                    'date_label' => $dateLabel,
                    'timezone' => $timezone,
                    'sight_id' => (int) $first->sight_id,
                    'sight_name' => $sightName,
                    'place_id' => (int) $first->sight_id,
                    'place_name' => $sightName,
                    'title' => trim($dateLabel.' '.$sightName),
                    'started_at' => $first->started_at?->toISOString(),
                    'laps_count' => $lapNumber,
                    'laps' => $laps,
                ];
            })
            ->sortByDesc(static fn (array $session): array => [
                $session['date'],
                $session['started_at'] ?? '',
            ])
            ->values()
            ->all();
    }

    private function timezoneForRun(MototrackRiderRun $run): string
    {
        return $this->resolveTimezone($run->sight?->locations?->time_zone);
    }

    private function resolveTimezone(?string $timezone): string
    {
        $timezone = trim((string) $timezone);

        if ($timezone !== '' && in_array($timezone, timezone_identifiers_list(), true)) {
            return $timezone;
        }

        return self::FALLBACK_TIMEZONE;
    }

    private function sightDisplayName(MototrackRiderRun $run): string
    {
        $name = trim((string) ($run->sight?->name ?? ''));

        if ($name !== '') {
            return preg_replace('/([^\s])\(/u', '$1 (', $name) ?? $name;
        }

        return 'Место #'.$run->sight_id;
    }

    private function formatClock(CarbonImmutable $time): string
    {
        return $time->format('H:i:s');
    }

    private function formatDuration(int $durationMs): string
    {
        $totalCentiseconds = (int) floor(max(0, $durationMs) / 10);
        $centiseconds = $totalCentiseconds % 100;
        $totalSeconds = (int) floor($totalCentiseconds / 100);
        $seconds = $totalSeconds % 60;
        $totalMinutes = (int) floor($totalSeconds / 60);
        $minutes = $totalMinutes % 60;
        $hours = (int) floor($totalMinutes / 60);

        $secondsPart = sprintf('%02d,%02d сек', $seconds, $centiseconds);

        if ($hours > 0) {
            return sprintf('%d ч %d мин %s', $hours, $minutes, $secondsPart);
        }

        return sprintf('%d мин %s', $minutes, $secondsPart);
    }
}
