<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Sight;
use Illuminate\Support\Facades\Http;

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

    // Для событий
    public function addStatusEvent(Request $request) 
    {   
        $vk_post = '';
        $event = Event::where('id', $request->event_id)->firstOrFail();
        $status = Status::all('id');
        $event->statuses()->updateExistingPivot( $status, ['last' => false]);
        $event->statuses()->attach($request->status_id, ['last' => true, 'descriptions' => $request->descriptions]);

        $status_post = Status::where('id', $request->status_id)->firstOrFail();

        if ($status_post->name === 'Опубликовано') {
            $url = 'https://api.vk.com/method/wall.post?message=' . $event->description . '&owner_id=' . getenv('VK_OWNER_ID') . '&lat=' . $event->latitude . '&long=' . $event->longitude . '&copyright=' . getenv('FRONT_APP_URL') . '&access_token=' . getenv('VK_TOKEN') . '&v=5.131';
            $vk_post = Http::post($url)->json();
        }

        return response()->json(['status' => 'success', 'event' => $request->event_id, 'add_status' => $request->status_id, 'vk' => $vk_post], 200);
    }
    // Для достопримечательностей
    public function addStatusSight(Request $request) 
    {
        $sight = Sight::where('id', $request->sight_id)->firstOrFail();
        $status = Status::all('id');
        $sight->statuses()->updateExistingPivot( $status, ['last' => false]);
        $sight->statuses()->attach($request->status_id, ['last' => true, 'descriptions' => $request->descriptions]);
        return response()->json(['status' => 'success', 'sight' => $request->sight_id, 'add_status' => $request->status_id, 'descriptions' => $request->descriptions], 200);
    }
}
