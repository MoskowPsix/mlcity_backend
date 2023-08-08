<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\Event;

class EventTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/event-types",
     *     tags={"Event-type"},
     *     summary="Get all event type",
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function getTypes(): \Illuminate\Http\JsonResponse
    {
        $types = EventType::all();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/events/getTypesId/{id}",
     *     tags={"Event-type"},
     *     summary="Get event type by id",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function getTypesId($id): \Illuminate\Http\JsonResponse
    {
        $types = EventType::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
    /**
     * @OA\Post(
     *     path="/events/addTypeEvent/{event_id}/{type_id}",
     *     tags={"Event-type"},
     *     summary="add type in event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$event_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    
    public function addTypeEvent($event_id, $type_id) 
    {
        $event = Event::where('id', $event_id)->firstOrFail();

        $event->types()->attach($type_id);
        return response()->json(['status' => 'success', 'event' => $event_id, 'add_type' => $type_id], 200);
    }
    /**
     * @OA\Put(
     *     path="/events/updateTypeEvent/{event_id}/{type_id}",
     *     tags={"Event-type"},
     *     summary="add type in event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$event_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function updateTypeEvent($event_id, $type_id) 
    {
        $event = Event::where('id', $event_id)->firstOrFail();

        $event->types()->sync($type_id);
        return response()->json(['status' => 'success', 'event' => $event_id, 'update_type' => $type_id], 200);
    }
    /**
     * @OA\Delete(
     *     path="/events/deleteTypeEvent/{event_id}/{type_id}",
     *     tags={"Event-type"},
     *     summary="add type in event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$event_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function deleteTypeUser($event_id, $type_id) 
    {
        $event = Event::where('id', $event_id)->firstOrFail();
        $event->types()->detach($type_id);
        $event->types()->detach();

        return response()->json(['status' => 'success', 'event' => $event_id, 'delete_type' => $type_id], 200);
    }
    
}
