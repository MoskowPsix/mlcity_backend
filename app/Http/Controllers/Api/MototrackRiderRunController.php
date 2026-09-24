<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mototrack\MototrackRiderRunResource;
use App\Models\MototrackRiderRun;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MototrackRiderRunController extends Controller
{
    private const DISPLAY_TIMEZONE = 'Europe/Moscow';

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
            ->with('sight:id,name')
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

    /**
     * @param  Collection<int, MototrackRiderRun>  $runs
     * @return list<array<string, mixed>>
     */
    private function buildSessions(Collection $runs): array
    {
        $timezone = self::DISPLAY_TIMEZONE;

        $grouped = $runs
            ->filter(static fn (MototrackRiderRun $run): bool => $run->started_at !== null)
            ->groupBy(function (MototrackRiderRun $run) use ($timezone): string {
                $date = CarbonImmutable::parse($run->started_at)->timezone($timezone)->format('Y-m-d');

                return $date.'#'.(int) $run->sight_id;
            });

        return $grouped
            ->map(function (Collection $sessionRuns) use ($timezone): array {
                $sorted = $sessionRuns
                    ->sortBy(fn (MototrackRiderRun $run) => [$run->started_at?->timestamp ?? 0, $run->id])
                    ->values();

                /** @var MototrackRiderRun $first */
                $first = $sorted->first();
                $localStart = CarbonImmutable::parse($first->started_at)->timezone($timezone);
                $dateKey = $localStart->format('Y-m-d');
                $dateLabel = $localStart->format('d.m.Y');
                $sightName = $this->sightDisplayName($first);

                $laps = [[
                    'type' => 'start',
                    'label' => 'старт',
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
                        'label' => $lapNumber.' круг',
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

    private function sightDisplayName(MototrackRiderRun $run): string
    {
        $name = trim((string) ($run->sight?->name ?? ''));

        if ($name !== '') {
            return $name;
        }

        return 'Место #'.$run->sight_id;
    }

    private function formatClock(CarbonImmutable $time): string
    {
        $centiseconds = (int) floor($time->millisecond / 10);

        return sprintf(
            '%02d:%02d:%02d:%02d',
            (int) $time->format('H'),
            (int) $time->format('i'),
            (int) $time->format('s'),
            $centiseconds,
        );
    }

    private function formatDuration(int $durationMs): string
    {
        $totalCentiseconds = (int) floor($durationMs / 10);
        $centiseconds = $totalCentiseconds % 100;
        $totalSeconds = (int) floor($totalCentiseconds / 100);
        $seconds = $totalSeconds % 60;
        $minutes = (int) floor($totalSeconds / 60);

        return sprintf('%02d:%02d:%02d', $minutes, $seconds, $centiseconds);
    }
}
