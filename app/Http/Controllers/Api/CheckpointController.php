<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CommissionInvite;
use App\Models\CheckpointGroup;
use App\Models\CheckpointParticipant;
use App\Models\Event;
use App\Models\EventCommissionMember;
use App\Models\User;
use App\Services\CheckpointImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckpointController extends Controller
{
    private function authorOnly(Request $request, int $id): Event
    {
        $event = Event::findOrFail($id);
        abort_unless((int) $event->user_id === (int) $request->user()->id, 403);
        abort_unless($request->user()->checkpoint_access, 403, 'Нет доступа к Checkpoint.');
        return $event;
    }

    private function accessible(Request $request, int $id, bool $enabled = true): Event
    {
        $event = Event::findOrFail($id);
        $user = $request->user();
        $isAuthor = (int) $event->user_id === (int) $user->id;
        if ($isAuthor) {
            abort_unless($user->checkpoint_access, 403, 'Нет доступа к Checkpoint.');
        } else {
            abort_unless(
                EventCommissionMember::where('event_id', $event->id)->where('user_id', $user->id)->exists(),
                403
            );
        }
        abort_if($enabled && !$event->checkpoint_enabled, 404);
        return $event;
    }

    private function group(Event $event, int $id): CheckpointGroup
    {
        return $event->checkpointGroups()->findOrFail($id);
    }

    private function participant(Event $event, int $id): CheckpointParticipant
    {
        return $event->checkpointParticipants()->findOrFail($id);
    }

    public function competitions(Request $request)
    {
        $user = $request->user();
        $events = Event::query()->where('checkpoint_enabled', true)->where(function ($query) use ($user) {
            if ($user->checkpoint_access) {
                $query->where('user_id', $user->id);
            }
            if (!$user->checkpoint_access) {
                $query->whereRaw('1 = 0');
            }
            $query->orWhereIn('id', EventCommissionMember::query()->where('user_id', $user->id)->select('event_id'));
        })
            ->with(['places.location', 'places.venue'])->withCount(['checkpointGroups', 'checkpointParticipants'])
            ->orderByDesc('updated_at')->get();

        return response()->json($events->map(fn (Event $event) => [
            'id' => $event->id,
            'name' => $event->name,
            'date_start' => $event->date_start ? Carbon::parse($event->date_start)->toDateString() : null,
            'date_end' => $event->date_end ? Carbon::parse($event->date_end)->toDateString() : null,
            'city' => $this->placeFields($event)['city'],
            'participants_count' => $event->checkpoint_participants_count,
            'groups_count' => $event->checkpoint_groups_count,
            'publish_allowed' => (int) $event->user_id === (int) $user->id ? (bool) $user->checkpoint_publish_override : true,
            'shared' => (int) $event->user_id !== (int) $user->id,
            'updated_at' => $event->updated_at?->toIso8601String(),
        ])->values());
    }

    public function competition(Request $request, int $id)
    {
        $event = $this->accessible($request, $id)->load([
            'places.location',
            'places.venue',
            'checkpointGroups' => fn ($query) => $query->withCount('participants'),
            'checkpointParticipants.group',
        ]);
        $groups = $event->checkpointGroups->sortBy([['sort_order', 'asc'], ['id', 'asc']])->values();
        $participants = $event->checkpointParticipants->sortBy('id')->values();

        return response()->json([
            'competition' => [
                'id' => $event->id,
                'name' => $event->name,
                'date_start' => $event->date_start ? Carbon::parse($event->date_start)->toDateString() : null,
                'date_end' => $event->date_end ? Carbon::parse($event->date_end)->toDateString() : null,
                'city' => $this->placeFields($event)['city'],
                'place_name' => $this->placeFields($event)['place_name'],
                'checkpoint_enabled' => true,
                'publish_allowed' => (int) $event->user_id === (int) $request->user()->id ? (bool) $request->user()->checkpoint_publish_override : true,
                'can_manage_access' => (int) $event->user_id === (int) $request->user()->id,
                'updated_at' => $event->updated_at?->toIso8601String(),
            ],
            'members' => (int) $event->user_id === (int) $request->user()->id ? $this->memberPayload($event) : [],
            'groups' => $groups->map(fn ($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'sort_order' => $group->sort_order,
                'participants_count' => $group->participants_count,
            ])->values(),
            'participants' => $participants->map(fn ($participant) => [
                'id' => $participant->id,
                'last_name' => $participant->last_name,
                'first_name' => $participant->first_name,
                'middle_name' => $participant->middle_name,
                'birth_date' => $participant->birth_date?->format('Y-m-d'),
                'city' => $participant->city,
                'group_id' => $participant->group_id,
                'group_name' => $participant->group?->name,
                'start_number' => $participant->start_number,
                'rfid' => $participant->rfid,
                'updated_at' => $participant->updated_at?->toIso8601String(),
            ])->values(),
        ]);
    }

    public function export(Request $request, int $id)
    {
        $payload = $this->competition($request, $id)->getData(true);
        $competition = $payload['competition'];
        unset($competition['can_manage_access']);
        $groups = [];
        foreach ($payload['groups'] as $group) {
            $groups[] = [
                'id' => $group['id'],
                'name' => $group['name'],
                'sort_order' => $group['sort_order'],
            ];
        }

        return response()->json([
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'competition' => $competition,
            'groups' => $groups,
            'participants' => $payload['participants'],
        ]);
    }

    public function settings(Request $request, int $id)
    {
        $event = $this->authorOnly($request, $id);
        return response()->json([
            'id' => $event->id,
            'name' => $event->name,
            'checkpoint_enabled' => (bool) $event->checkpoint_enabled,
        ]);
    }

    public function updateSettings(Request $request, int $id)
    {
        $event = $this->authorOnly($request, $id);
        $data = $request->validate(['checkpoint_enabled' => 'required|boolean']);
        $event->update($data);
        return response()->json(['id' => $event->id, 'checkpoint_enabled' => (bool) $event->checkpoint_enabled]);
    }

    public function storeGroup(Request $request, int $id)
    {
        $event = $this->accessible($request, $id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('checkpoint_groups')->where('event_id', $id)],
            'sort_order' => 'sometimes|integer',
        ]);
        $group = $event->checkpointGroups()->create($data);
        $event->touch();
        return response()->json($group, 201);
    }

    public function updateGroup(Request $request, int $id, int $groupId)
    {
        $event = $this->accessible($request, $id);
        $group = $this->group($event, $groupId);
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('checkpoint_groups')->where('event_id', $id)->ignore($group->id)],
            'sort_order' => 'sometimes|integer',
        ]);
        $group->update($data);
        $event->touch();
        return response()->json($group);
    }

    public function deleteGroup(Request $request, int $id, int $groupId)
    {
        $event = $this->accessible($request, $id);
        $group = $this->group($event, $groupId);
        abort_if($group->participants()->exists(), 422, 'Группа содержит участников.');
        $group->delete();
        $event->touch();
        return response()->noContent();
    }

    private function participantData(Request $request, Event $event, ?CheckpointParticipant $participant = null): array
    {
        $required = $participant ? 'sometimes|required' : 'required';
        return $request->validate([
            'last_name' => "$required|string|max:255",
            'first_name' => "$required|string|max:255",
            'middle_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'city' => 'nullable|string|max:255',
            'group_id' => ['nullable', 'integer', Rule::exists('checkpoint_groups', 'id')->where('event_id', $event->id)],
            'start_number' => [$required, 'string', 'max:255', Rule::unique('checkpoint_participants')->where('event_id', $event->id)->ignore($participant?->id)],
            'rfid' => 'nullable|string|max:255',
        ]);
    }

    public function storeParticipant(Request $request, int $id)
    {
        $event = $this->accessible($request, $id);
        $participant = $event->checkpointParticipants()->create($this->participantData($request, $event));
        $event->touch();
        return response()->json($participant, 201);
    }

    public function updateParticipant(Request $request, int $id, int $participantId)
    {
        $event = $this->accessible($request, $id);
        $participant = $this->participant($event, $participantId);
        $participant->update($this->participantData($request, $event, $participant));
        $event->touch();
        return response()->json($participant);
    }

    public function deleteParticipant(Request $request, int $id, int $participantId)
    {
        $event = $this->accessible($request, $id);
        $this->participant($event, $participantId)->delete();
        $event->touch();
        return response()->noContent();
    }

    public function template(Request $request, int $id, CheckpointImportService $service)
    {
        $this->accessible($request, $id);
        $path = tempnam(sys_get_temp_dir(), 'checkpoint');
        $service->writeTemplate($path);
        return response()->download($path, 'checkpoint-participants.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function preview(Request $request, int $id, CheckpointImportService $service)
    {
        $event = $this->accessible($request, $id);
        $prepared = $service->prepare($request, $event);
        if ($prepared['errors']) {
            return response()->json(['message' => 'Файл содержит ошибки', 'errors' => $prepared['errors']], 422);
        }
        return response()->json($prepared);
    }

    public function import(Request $request, int $id, CheckpointImportService $service)
    {
        $event = $this->accessible($request, $id);
        $prepared = $service->prepare($request, $event);
        if ($prepared['mapping_required'] || $prepared['errors']) {
            return $this->importResponse($prepared);
        }
        $count = $service->commit($event, $prepared['rows']);
        return response()->json(['imported' => $count]);
    }

    private function importResponse(array $prepared)
    {
        if ($prepared['errors']) {
            return response()->json(['message' => 'Файл содержит ошибки', 'errors' => $prepared['errors']], 422);
        }
        if ($prepared['mapping_required']) {
            return response()->json($prepared, 422);
        }
        return response()->json($prepared);
    }

    public function storeMember(Request $request, int $id)
    {
        $event = $this->authorOnly($request, $id);
        $email = mb_strtolower(trim((string) $request->validate(['email' => 'required|email'])['email']));
        $author = $request->user();
        abort_if(mb_strtolower((string) $author->email) === $email, 422, 'Себе комиссию открывать не нужно, она уже ваша.');
        abort_if(
            EventCommissionMember::where('event_id', $event->id)->where('email', $email)->exists(),
            409,
            'Этой почте комиссия уже открыта.'
        );

        $invited = User::whereRaw('lower(email) = ?', [$email])->first();
        $token = $invited ? null : Str::random(40);
        $member = $event->commissionMembers()->create([
            'user_id' => $invited?->id,
            'granted_by' => $author->id,
            'email' => $email,
            'token' => $token,
        ]);
        $member->load('user');
        $pending = $invited === null;
        $front = rtrim((string) env('FRONT_APP_URL', 'http://127.0.0.1:8100'), '/');
        $url = $pending
            ? $front.'/cabinet/checkpoint/invite/'.$token
            : $front.'/cabinet/events/'.$event->id.'/commission';
        $mailSent = true;
        try {
            Mail::to($email)->send(new CommissionInvite($event->name, $url, $pending));
        } catch (\Throwable $e) {
            $mailSent = false;
            Log::error('commission invite mail failed: '.$e->getMessage());
        }

        return response()->json([
            'status' => $pending ? 'invited' : 'added',
            'mail_sent' => $mailSent,
            'member' => $this->memberRow($member),
        ], 201);
    }

    public function destroyMember(Request $request, int $id, int $memberId)
    {
        $event = $this->authorOnly($request, $id);
        $event->commissionMembers()->whereKey($memberId)->firstOrFail()->delete();
        return response()->noContent();
    }

    public function acceptInvite(Request $request, string $token)
    {
        $member = EventCommissionMember::where('token', $token)->firstOrFail();
        $user = auth('sanctum')->user();
        abort_unless($user && mb_strtolower((string) $user->email) === $member->email, 403, 'Приглашение отправлено на другую почту.');
        $member->update(['user_id' => $user->id, 'token' => null]);
        return response()->json(['event_id' => $member->event_id]);
    }

    private function memberPayload(Event $event): array
    {
        return $event->commissionMembers()->with('user')->orderBy('id')->get()->map(fn (EventCommissionMember $member) => $this->memberRow($member))->values()->all();
    }

    private function memberRow(EventCommissionMember $member): array
    {
        return [
            'id' => $member->id,
            'email' => $member->email,
            'name' => $member->user?->name,
            'accepted' => $member->user_id !== null,
            'pending' => $member->user_id === null,
        ];
    }

    private function placeFields(Event $event): array
    {
        $place = $event->places->first();
        return [
            'city' => $place?->location?->name,
            'place_name' => $place?->venue?->name ?: $place?->address,
        ];
    }
}
