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
    /**
     * @OA\Get(
     *     path="/statuses",
     *     tags={"Statuses"},
     *     summary="Get all statuses",
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
    public function getStatuses(): \Illuminate\Http\JsonResponse
    {
        $statuses = Status::all();

        return response()->json([
            'status'     => 'success',
            'statuses'   => $statuses
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/getStatusId/{id}",
     *     tags={"Statuses"},
     *     summary="Get statuses by id",
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
    public function getStatusId($id): \Illuminate\Http\JsonResponse
    {
        $status = Status::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'statuses'          => $status
        ], 200);
    }
     /**
     * @OA\Post(
     *     path="/events/addStatusEvent",
     *     tags={"Statuses"},
     *     summary="Add statuses for event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="event_id",
     *         in="query",
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
    // Для событий
    public function addStatusEvent(Request $request) 
    {   
        // $vk_post['response']['post_id'] = '';
        $event = Event::where('id', $request->event_id)->firstOrFail();
        info($event);
        $statuses_all = Status::all();
        $status = Status::where('name', $request->status)->firstOrFail();
        $event->statuses()->updateExistingPivot($statuses_all, ['last' => false]);
        $event->statuses()->attach($status->id, ['last' => true, 'descriptions' => $request->descriptions]);

        // $status_post = Status::where('id', $request->status_id)->firstOrFail();
        // $vk_post['response']['post_id'] = $event->vk_post_id;

        // if ($status_post->name === 'Опубликовано' && empty($event->vk_post_id)) {
        //     $url = 'https://api.vk.com/method/wall.post?message=' . $event->description . '&owner_id=' . getenv('VK_OWNER_ID') . '&lat=' . $event->latitude . '&long=' . $event->longitude . '&copyright=' . getenv('FRONT_APP_URL') . '&access_token=' . getenv('VK_TOKEN') . '&v=5.131';
        //     $vk_post = Http::post($url)->json();
        //     $event->vk_post_id = $vk_post['response']['post_id'];
        //     $event->vk_group_id = getenv('VK_OWNER_ID');
        //     $event->save();
        // }

        return response()->json(
            [
                'status' => 'success', 
                'event' => $request->event_id, 
                'add_status' => $request->status, 
                'descriptions' => $request->descriptions,
                // 'vk_post_id' => $vk_post['response']['post_id'], 
                // 'vk_group_id' => getenv('VK_OWNER_ID') 
        ], 200);
    }
    /**
     * @OA\Post(
     *     path="/sights/addStatusEvent",
     *     tags={"Statuses"},
     *     summary="Add statuses for sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="sight_id",
     *         in="query",
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
    // Для достопримечательностей
    public function addStatusSight(Request $request) 
    {
        $vk_post['response']['post_id'] = '';
        $sight = Sight::where('id', $request->sight_id)->firstOrFail();
        $status = Status::all('id');
        $sight->statuses()->updateExistingPivot( $status, ['last' => false]);
        $sight->statuses()->attach($request->status_id, ['last' => true, 'descriptions' => $request->descriptions]);

        $status_post = Status::where('id', $request->status_id)->firstOrFail();

        // if ($status_post->name === 'Опубликовано' && empty($event->vk_post_id)) {
        //     $url = 'https://api.vk.com/method/wall.post?message=' . $sight->description . '&owner_id=' . getenv('VK_OWNER_ID') . '&lat=' . $sight->latitude . '&long=' . $sight->longitude . '&copyright=' . getenv('FRONT_APP_URL') . '&access_token=' . getenv('VK_TOKEN') . '&v=5.131';
        //     $vk_post = Http::post($url)->json();
        //     $sight->vk_group_id = $vk_post['response']['post_id'];
        //     $sight->vk_post_id = getenv('VK_OWNER_ID');
        //     $sight->save();
        // }

        return response()->json(
            [
                'status' => 'success', 
                'sight' => $request->sight_id, 
                'add_status' => $request->status_id, 
                'descriptions' => $request->descriptions,
                'vk_post_id' => $vk_post['response']['post_id'],    
                'vk_group_id' => getenv('VK_OWNER_ID') 
            ], 200);
    }
}
