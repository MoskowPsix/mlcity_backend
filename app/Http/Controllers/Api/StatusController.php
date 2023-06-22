<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

use App\Models\Status;

class StatusController extends Controller
{
    public function getStatuses(): \Illuminate\Http\JsonResponse
    {
        $statuses = Status::all();

        return response()->json([
            'status'     => 'success',
            'statuses'   => $statuses
        ], 200);
    }

    public function getStatusId($id): \Illuminate\Http\JsonResponse
    {
        $status = Status::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'statuses'          => $status
        ], 200);
    }
    public function addStatusEvent($event_id, $status_id) 
    {
        $event = Event::where('id', $event_id)->firstOrFail();

        $event->statuses()->attach($status_id);
        return response()->json(['status' => 'success', 'event' => $event_id, 'add_status' => $status_id], 200);
    }
    public function updateStatusEvent(Request $request) 
    {
        $event = Event::where('id', $request->event_id)->firstOrFail();
            $event->statuses()->sync([$request->status_id => ['descriptions' => $request->descriptions]]);
            $event->statuses()->sync($request->status_id);
        return response()->json(['status' => 'success', 'event' => $request->event_id, 'update_status' => $request->status_id, 'descriptions' => $request->descriptions], 200);
    }
    public function deleteStatusEvent($event_id, $status_id) 
    {
        $event = Event::where('id', $event_id)->firstOrFail();
        $event->statuses()->detach($status_id);
        $event->statuses()->detach();

        return response()->json(['status' => 'success', 'event' => $event_id, 'delete_status' => $status_id], 200);
    }
}
