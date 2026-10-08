<?php
namespace App\Services;

use App\Events\CheckpointResultsUpdated;
use App\Models\CheckpointHeat;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
            'results' => 'nullable|array',
            'results.*.participant_id' => ['required','integer', Rule::exists('checkpoint_participants', 'id')->where('event_id', $event->id)],
            'results.*.place' => 'nullable|integer|min:1',
            'results.*.result' => 'nullable|string|max:255',
            'results.*.result_value_ms' => 'nullable|integer|min:0',
        ]);
        $wasNew = DB::transaction(function () use ($data, $event) {
            if (!DB::table('checkpoint_result_events')->where('event_id', $event->id)->where('external_event_id', $data['event_id'])->doesntExist()) return false;
            DB::table('checkpoint_result_events')->insert(['event_id' => $event->id, 'external_event_id' => $data['event_id'], 'occurred_at' => Carbon::parse($data['occurred_at']), 'created_at' => now(), 'updated_at' => now()]);
            $heat = CheckpointHeat::updateOrCreate(['event_id' => $event->id, 'external_id' => $data['heat']['external_id']], ['name' => $data['heat']['name']]);
            if ($data['type'] === 'heat.open') $heat->update(['status' => 'live', 'started_at' => $data['occurred_at']]);
            if ($data['type'] === 'heat.finish') $heat->update(['status' => 'finished', 'finished_at' => $data['occurred_at']]);
            if ($data['type'] === 'live.close' && $heat->status === 'live') $heat->update(['status' => 'finished', 'finished_at' => $data['occurred_at']]);
            foreach ($data['results'] ?? [] as $result) {
                $heat->results()->updateOrCreate(['participant_id' => $result['participant_id']], [
                    'place' => $result['place'] ?? null, 'result' => $result['result'] ?? null, 'result_value_ms' => $result['result_value_ms'] ?? null,
                ]);
            }
            $event->touch();
            return true;
        });
        $state = $this->state($event->fresh());
        if ($wasNew) broadcast(new CheckpointResultsUpdated($event->id, $state));
        return ['accepted' => $wasNew, 'state' => $state];
    }

    public function state(Event $event): array
    {
        $heats = $event->checkpointHeats()->with(['results.participant.group'])->orderBy('started_at')->orderBy('id')->get();
        $serialize = fn ($heat) => ['id' => $heat->external_id, 'name' => $heat->name, 'status' => $heat->status, 'started_at' => $heat->started_at?->toIso8601String(), 'finished_at' => $heat->finished_at?->toIso8601String(), 'results' => $heat->results->sortBy(fn ($r) => $r->place ?? PHP_INT_MAX)->values()->map(fn ($r) => ['participant_id' => $r->participant_id, 'start_number' => $r->participant?->start_number, 'name' => trim(($r->participant?->last_name ?? '').' '.($r->participant?->first_name ?? '')), 'group_name' => $r->participant?->group?->name, 'place' => $r->place, 'result' => $r->result, 'result_value_ms' => $r->result_value_ms])->values()];
        $live = $heats->firstWhere('status', 'live');
        return ['event_id' => $event->id, 'live' => $live ? $serialize($live) : null, 'heats' => $heats->where('status', 'finished')->map($serialize)->values()];
    }
}
