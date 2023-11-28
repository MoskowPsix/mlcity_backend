<?php

namespace App\Http\Controllers\Api;

use App\Filters\Event\EventDate;
use App\Filters\Event\EventName;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventSponsor;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryContent\GetHistoryContentRequest;
use App\Models\Event;
use App\Models\HistoryContent;
use App\Models\HistoryPlace;
use App\Models\Sight;
use App\Models\Status;
use Illuminate\Console\View\Components\Info;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class HistoryContentController extends Controller
{
    public function getHistoryContent(GetHistoryContentRequest $request){
        $total = 0;
        $page = $request->page;
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;
        $historyContents = HistoryContent::query();

        $response = app(Pipeline::class)
                    ->send($historyContents)
                    ->through([
                        EventName::class,
                        EventDate::class,
                        EventSponsor::class,
                        EventSearchText::class
                    ])
                    ->via('apply')
                    ->then(function ($historyContents) use ($page, $limit, $total){
                        $total = $historyContents->count();
                        $historyContents = $historyContents->orderBy('created_at')->cursorPaginate($limit, ['*'], 'page', $page);
                        return [$historyContents, $total];
                    });
        return response()->json(["status"=>"success", "historyContens" => $response[0], "total" => $response[1]],200);
    }

    public function createHistoryContent(Request $request){
        $request = $request->toArray();
        $request['history_content']["user_id"] = auth("api")->user()->id;
        $status_id = Status::where("name", "На модерации")->first()->id;
        
        if($request["type"] == "Event"){
            $event = Event::where('id',$request['id'])->first();
            // info($request);
            $historyContent = $event->historyContents()->create($request['history_content']);
            $historyContent->historyContentStatuses()->create([
                "status_id" => $status_id
            ]);

            if(array_key_exists("history_place",$request)){
                $historyPlace = $historyContent->historyPlaces()->create($request['history_place']);

                if (array_key_exists("history_seance", $request)){
                    $historySeance = $historyPlace->historySeances()->create($request["history_seance"]);
                }
            }

            if (array_key_exists("history_price",$request)){
                $historyContent->historyPrices()->create($request["history_price"]);
            }

        }
        else if($request["type"] == "Sight"){
            $sight = Sight::where('id',$request['id'])->first();
            $historyContent = $sight->historyContents()->create($request['history_content']);
        }
        
        return response()->json(["status"=>"success"],201);
    }
}
