<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EventStatusChanged;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Support\Facades\Http;

use App\Models\Status;
use Illuminate\Support\Facades\Mail;

class StatusController extends Controller
{
    public function getStatuses(): \Illuminate\Http\JsonResponse
    {
        $statuses = [];
        if(auth('api')->user()) {
            if (auth('api')->user()->roles) {
                if(count(auth('api')->user()->roles)>0){
                    switch (auth('api')->user()->roles[0]->name) {
                        case "root":
                            $statuses = Status::all()->toArray();
                            usort($statuses, [$this, "sortStatuses"]);
                        break;
                        case "Admin":
                            $statuses = Status::all();
                        break;
                        case "Moderator":
                            $statuses = Status::all();
                        break;
                    }
                }
                else{
                    $statuses = Status::where('name', 'Черновик')->orWhere('name', 'Новое')->get();
                }
            } else {
                $statuses = Status::where('name', 'Черновик')->orWhere('name', 'Новое')->get();
            }
        } else {
            $statuses = Status::where('name', 'Черновик')->orWhere('name', 'Новое')->get();
        }

        return response()->json([
            'status'     => 'success',
            'statuses'   => $statuses,
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
        // $vk_post['response']['post_id'] = '';
        $event = Event::where('id', $request->event_id)->firstOrFail();
        $statuses_all = Status::all();
        $status = Status::where('name', $request->status)->firstOrFail();
        $event->statuses()->updateExistingPivot($statuses_all, ['last' => false]);
        $user = User::findOrFail($event->user_id);
        $event->statuses()->attach($status->id, ['last' => true, 'descriptions' => $request->descriptions]);
        Mail::to($user->email)->send(new EventStatusChanged($status->name, $event->name));
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

    // Для достопримечательностей
    public function addStatusSight(Request $request)
    {
        $vk_post['response']['post_id'] = '';
        $sight = Sight::where('id', $request->sight_id)->firstOrFail();
        $statuses_all = Status::all();
        $status = Status::where('name',$request->get("status"))->firstOrFail();
        $sight->statuses()->updateExistingPivot( $statuses_all, ['last' => false]);
        $sight->statuses()->attach($status->id, ['last' => true, 'descriptions' => $request->descriptions]);


        return response()->json(
            [
                'status' => 'success',
                'sight' => $request->sight_id,
                'descriptions' => $request->descriptions,
            ], 200);
    }
    public function addStatusSightForMoon(Request $request)
    {
        $sight = Sight::where('id', $request->sight_id)->firstOrFail();
        $statuses_all = Status::all();
        $status = Status::where('name',$request->status_id)->firstOrFail();
        $sight->statuses()->updateExistingPivot( $statuses_all, ['last' => false]);
        $sight->statuses()->attach($status->id, ['last' => true, 'descriptions' => $request->descriptions]);


        return response()->json(
            [
                'status' => 'success',
                'sight' => $request->sight_id,
                'descriptions' => $request->descriptions,
            ], 200);
    }

    private function sortStatuses($a, $b)
    {
        $order = array("Новое", "Изменено", "Опубликовано", "Черновик", "Заблокировано", "В архиве", "Отказ");
        $pos1 = array_search($a['name'], $order);
        $pos2 = array_search($b['name'], $order);

        return $pos1 - $pos2;
    }
}
