<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\View;

class ViewController extends Controller
{
    public function addView(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->time >= 3.141) {
            $view = View::create([
                'user_id' =>   auth('api')->user()->id,
                'event_id' =>  $request->event_id,
                'sight_id' =>  $request->sight_id,
                'time_view' => $request->time,
            ]);

            return response()->json([
                'status' => 'success',
                'view_event' => $view
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error time'
            ], 403);
        }
    }

}
