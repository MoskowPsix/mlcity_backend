<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function getEvents(): \Illuminate\Http\JsonResponse
    {
        $events = EventType::all();

        return response()->json([
            'status'        => 'success',
            'events'          => $events
        ], 200);
    }
}
