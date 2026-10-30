<?php
namespace App\Services;

use App\Events\CheckpointResultsUpdated;
use App\Models\CheckpointHeat;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CheckpointResultsService
{
    public function ingest(Request $request, Event $event): array
    {
        $data = $request->validate([
            'event_id' => 'required|uuid',
            'type' => ['required', Rule::in(['heat.open', 'results.upsert', 'heat.finish', 'live.close'])],
            'occurred_at' => 'required|date',
            'heat.external_id' => 'required|string|max:255',
            'heat.name' => 'required|string|max:255',
            'heat.type_slug' => 'nullable|string|max:255',
            'heat.laps_target' => 'nullable|integer|min:0',
            'results' => 'nullable|array',
            'results.*.participant_id' => ['required','integer', Rule::exists('checkpoint_participants', 'id')->where('event_id', $event->id)],
            'results.*.place' => 'nullable|integer|min:1',
            'results.*.laps' => 'nullable|integer|min:0',
            'results.*.result' => 'nullable|string|max:255',
            'results.*.result_value_ms' => 'nullable|numeric|min:0',
            'results.*.best_lap_time_ms' => 'nullable|numeric|min:0',
            'results.*.last_lap_time_ms' => 'nullable|numeric|min:0',
            'results.*.lap_times' => 'nullable|array',
            'results.*.lap_times.*.lap_number' => 'required_with:results.*.lap_times|integer|min:1',
            'results.*.lap_times.*.lap_time_ms' => 'required_with:results.*.lap_times|numeric|min:0',
            'results.*.lap_times.*.timestamp_ms' => 'required_with:results.*.lap_times|numeric|min:0',
            'results.*.lap_times.*.position_on_lap' => 'nullable|integer|min:1',
            'results.*.lap_times.*.is_manual' => 'nullable|boolean',
        ]);
        foreach ($data['results'] ?? [] as $i => $result) {
            foreach (['result_value_ms', 'best_lap_time_ms', 'last_lap_time_ms'] as $msKey) {
                if (array_key_exists($msKey, $result) && $result[$msKey] !== null) {
                    $data['results'][$i][$msKey] = (int) round((float) $result[$msKey]);
                }
            }
            if (array_key_exists('laps', $result) && $result['laps'] !== null) {
                $data['results'][$i]['laps'] = (int) $result['laps'];
            }
            foreach ($result['lap_times'] ?? [] as $j => $lap) {
                foreach (['lap_time_ms', 'timestamp_ms'] as $msKey) {
                    if (array_key_exists($msKey, $lap) && $lap[$msKey] !== null) {
                        $data['results'][$i]['lap_times'][$j][$msKey] = (int) round((float) $lap[$msKey]);
                    }
                }
            }
        }
        $wasNew = DB::transaction(function () use ($data, $event) {
            if (!DB::table('checkpoint_result_events')->where('event_id', $event->id)->where('external_event_id', $data['event_id'])->doesntExist()) return false;
            DB::table('checkpoint_result_events')->insert(['event_id' => $event->id, 'external_event_id' => $data['event_id'], 'occurred_at' => Carbon::parse($data['occurred_at']), 'created_at' => now(), 'updated_at' => now()]);
            $heatAttrs = ['name' => $data['heat']['name']];
            if (!empty($data['heat']['type_slug'])) {
                $heatAttrs['type_slug'] = $data['heat']['type_slug'];
            }
            if (array_key_exists('laps_target', $data['heat'])) {
                $heatAttrs['laps_target'] = $data['heat']['laps_target'];
            }
            $heat = CheckpointHeat::updateOrCreate(
                ['event_id' => $event->id, 'external_id' => $data['heat']['external_id']],
                $heatAttrs,
            );
            if ($data['type'] === 'heat.open') {
                // Один live на событие — иначе firstWhere показывает старый эфир без новых времён.
                CheckpointHeat::query()
                    ->where('event_id', $event->id)
                    ->where('status', 'live')
                    ->where('id', '!=', $heat->id)
                    ->update(['status' => 'finished', 'finished_at' => $data['occurred_at']]);
                $heat->update(['status' => 'live', 'started_at' => $data['occurred_at']]);
                // Новый старт — старые строки (abandon / прошлый прогон того же external_id) не оставляем.
                $heat->results()->delete();
            }
            if ($data['type'] === 'heat.finish') $heat->update(['status' => 'finished', 'finished_at' => $data['occurred_at']]);
            // abandon: live.close пока status=live. После heat.finish статус уже finished — результаты не трогаем.
            if ($data['type'] === 'live.close' && $heat->status === 'live') {
                $heat->results()->delete();
                $heat->update(['status' => 'finished', 'finished_at' => $data['occurred_at']]);
            }
            $incomingResults = $data['results'] ?? null;
            if (is_array($incomingResults) && in_array($data['type'], ['results.upsert', 'heat.finish'], true)) {
                $ids = array_values(array_map('intval', array_column($incomingResults, 'participant_id')));
                if ($ids === []) {
                    $heat->results()->delete();
                } else {
                    $heat->results()->whereNotIn('participant_id', $ids)->delete();
                }
            }
            foreach ($incomingResults ?? [] as $result) {
                $heat->results()->updateOrCreate(['participant_id' => $result['participant_id']], [
                    'place' => $result['place'] ?? null,
                    'laps' => $result['laps'] ?? null,
                    'result' => $result['result'] ?? null,
                    'result_value_ms' => $result['result_value_ms'] ?? null,
                    'best_lap_time_ms' => $result['best_lap_time_ms'] ?? null,
                    'last_lap_time_ms' => $result['last_lap_time_ms'] ?? null,
                    'lap_times' => $result['lap_times'] ?? null,
                ]);
            }
            $event->touch();
            return true;
        });
        $state = $this->state($event->fresh());
        if ($wasNew) {
            try {
                broadcast(new CheckpointResultsUpdated($event->id, $state));
            } catch (\Throwable $e) {
                Log::warning('checkpoint.broadcast_failed', [
                    'event_id' => $event->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }
        return ['accepted' => $wasNew, 'state' => $state];
    }

    public function state(Event $event): array
    {
        $heats = $event->checkpointHeats()->with(['results.participant.group'])->orderBy('started_at')->orderBy('id')->get();
        $serialize = fn ($heat) => [
            'id' => $heat->external_id,
            'name' => $heat->name,
            'type_slug' => $heat->type_slug,
            'laps_target' => $heat->laps_target,
            'status' => $heat->status,
            'started_at' => $heat->started_at?->toIso8601String(),
            'finished_at' => $heat->finished_at?->toIso8601String(),
            'results' => $heat->results->sortBy(fn ($r) => $r->place ?? PHP_INT_MAX)->values()->map(fn ($r) => [
                'participant_id' => $r->participant_id,
                'start_number' => $r->participant?->start_number,
                'name' => trim(($r->participant?->last_name ?? '').' '.($r->participant?->first_name ?? '')),
                'group_name' => $r->participant?->group?->name,
                'place' => $r->place,
                'laps' => $r->laps,
                'result' => $r->result,
                'result_value_ms' => $r->result_value_ms,
                'best_lap_time_ms' => $r->best_lap_time_ms,
                'last_lap_time_ms' => $r->last_lap_time_ms,
                'lap_times' => $r->lap_times ?? [],
            ])->values(),
        ];
        $live = $heats->firstWhere('status', 'live');
        $finished = $heats->where('status', 'finished')
            ->sortByDesc(fn ($h) => $h->finished_at?->getTimestamp() ?? $h->started_at?->getTimestamp() ?? 0)
            ->values();
        return ['event_id' => $event->id, 'live' => $live ? $serialize($live) : null, 'heats' => $finished->map($serialize)->values()];
    }
}
