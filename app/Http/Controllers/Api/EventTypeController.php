<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\Event;

class EventTypeController extends Controller
{

    public function getTypes(): \Illuminate\Http\JsonResponse
    {
        $types = EventType::where('etype_id')->with('etypes')->orderBy('order', 'ASC')->get();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }

    public function getTypesId($id): \Illuminate\Http\JsonResponse
    {
        $types = EventType::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }

    public function addTypeEvent($event_id, $type_id)
    {
        $event = Event::where('id', $event_id)->firstOrFail();

        $event->types()->attach($type_id);
        return response()->json(['status' => 'success', 'event' => $event_id, 'add_type' => $type_id], 200);
    }

    public function updateTypeEvent($event_id, $type_id)
    {
        $event = Event::where('id', $event_id)->firstOrFail();

        $event->types()->sync($type_id);
        return response()->json(['status' => 'success', 'event' => $event_id, 'update_type' => $type_id], 200);
    }
    public function deleteTypeUser($event_id, $type_id)
    {
        $event = Event::where('id', $event_id)->firstOrFail();
        $event->types()->detach($type_id);
        $event->types()->detach();

        return response()->json(['status' => 'success', 'event' => $event_id, 'delete_type' => $type_id], 200);
    }

}
