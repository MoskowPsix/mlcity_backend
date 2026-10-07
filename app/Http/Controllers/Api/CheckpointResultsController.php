<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCommissionMember;
use App\Services\CheckpointResultsService;
use Illuminate\Http\Request;
class CheckpointResultsController extends Controller {
    public function show(int $id, CheckpointResultsService $service) { $event = Event::findOrFail($id); abort_unless($event->checkpoint_enabled, 404); return response()->json($service->state($event)); }
    public function ingest(Request $request, int $id, CheckpointResultsService $service) {
        $event = Event::findOrFail($id);
        $user = $request->user();
        $isAuthor = (int) $event->user_id === (int) $user->id;
        $isMember = EventCommissionMember::where('event_id', $event->id)->where('user_id', $user->id)->exists();
        abort_unless($isAuthor || $isMember, 403);
        abort_unless($event->checkpoint_enabled, 404);
        if ($isAuthor) {
            abort_unless($user->checkpoint_publish_override, 403, 'Нет права публикации результатов.');
        }
        return response()->json($service->ingest($request, $event));
    }
}
