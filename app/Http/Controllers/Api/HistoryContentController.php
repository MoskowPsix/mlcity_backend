<?php

namespace App\Http\Controllers\Api;

use App\Filters\HistoryContent\HistoryContentAuthor;
use App\Filters\HistoryContent\HistoryContentStatuses;
use App\Filters\HistoryContent\HistoryContentStatusesLast;
use App\Filters\HistoryContent\HistoryContentGeoPositionAreaHistoryPlace;
use App\Filters\Event\EventDate;
use App\Filters\Event\EventName;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventSponsor;
use App\Filters\HistoryContent\HistoryContentEventTypes;
use App\Filters\HistoryContent\HistoryContentSightTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryContent\GetHistoryContentRequest;
use App\Models\Event;
use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\HistoryPlace;
use App\Models\HistorySeance;
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
        $historyContents = HistoryContent::query()->with('user');

        $response = app(Pipeline::class)
                    ->send($historyContents)
                    ->through([
                        EventName::class,
                        EventDate::class,
                        EventSponsor::class,
                        EventSearchText::class,
                        HistoryContentAuthor::class,
                        HistoryContentStatuses::class,
                        HistoryContentStatusesLast::class,
                        HistoryContentGeoPositionAreaHistoryPlace::class,
                        HistoryContentEventTypes::class,
                        HistoryContentSightTypes::class
                        
                    ])
                    ->via('apply')
                    ->then(function ($historyContents) use ($page, $limit, $total){
                        $total = $historyContents->count();
                        $historyContents = $historyContents->orderBy('created_at')->cursorPaginate($limit, ['*'], 'page', $page);
                        return [$historyContents, $total];
                    });
        return response()->json(["status"=>"success", "historyContents" => $response[0], "total" => $response[1]],200);
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

            if(array_key_exists("history_files", $request)){
                $this->saveLocalFilesImg($historyContent, $request["history_files"]);
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

    public function acceptHistoryContent(Request $request){
        $data = $request->toArray();
        $status = $request["status"];
        $historyContentId = $request["historyContent"]['id'];

        if ($status == "Опубликованно")
        {
            $historyContent = HistoryContent::find($historyContentId);
            $historyParent = $historyContent->historyContentable;
            $historyRawData = $historyContent->toArray();

            unset($historyRawData['created_at']);
            unset($historyRawData['updated_at']);
            
            unset($historyRawData['id']);
            unset($historyRawData[ 'history_contentable_id']);
            unset($historyRawData[ 'history_contentable_type']);
            unset($historyRawData['history_contentable']);

            $historyData = [];

            foreach($historyRawData as $key=>$data){
                if(!empty($data)){
                    $historyData[$key] = $data;
                }
            }

            if(!empty($historyData)){
                if(isset($historyData["on_delete"]) && $historyData["on_delete"] == true){
                    $historyParent->delete();
                }
                else{
                    $historyParent->update($historyData);
                }
                
                
            }


            

            if(isset($request['historyPlace']))
            {
                $historyPlaceId = $request["historyPlace"]["id"];
                $historyPlace = HistoryPlace::find($historyPlaceId);
                $historyPlaceParent = $historyPlace->place;
                $historyRawData = $historyPlace->ToArray();

                unset($historyRawData['created_at']);
                unset($historyRawData['updated_at']);
                unset($historyRawData["history_content_id"]);
                unset($historyRawData["place"]);
                unset($historyRawData["place_id"]);
                unset($historyRawData['id']);
         

                $historyData = [];

                foreach($historyRawData as $key=>$data){
                    if(!empty($data)){
                        $historyData[$key] = $data;

                    }
                }

                if(!empty($historyData)){
                    if(isset($historyData["on_delete"]) && $historyData["on_delete"] == true){
                        $historyPlaceParent->delete();
                    }
                    else{
                        $historyPlaceParent->update($historyData);
                    }
                    
                }

                if(isset($request["historySeance"]))
                {
                    $historySeanceId = $request["historySeance"]['id'];
                    $historySeance = HistorySeance::find($historySeanceId);
                    $historySeanceParent = $historySeance->seance;

                    $historyRawData = $historySeance->toArray();
                    unset($historyRawData['created_at']);
                    unset($historyRawData['updated_at']);
                    unset($historyRawData['history_place_id']);
                    unset($historyRawData['id']);
                    unset($historyRawData['seance']);
                    unset($historyRawData["seance_id"]);



                    $historyData = [];



                    foreach($historyRawData as $key=>$data){
                        if(!empty($data)){
                            $historyData[$key] = $data;
                        }
                    }

                    info($historyData);

                    if(!empty($historyData)){
                        
                        if(isset($historyData["on_delete"]) && $historyData["on_delete"] == true){
                            $historySeanceParent->delete();
                        }
                        else{
                            $historySeanceParent->update([
                                "dateStart" => $historySeance["date_start"]
                            ]);
                        }
                    }
                    


                }
            }


            return response()->json(["status"=>"success"],201);

        }
    }

    
    private function saveLocalFilesImg($historyContent, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_');

            $path = $file->store('events/'.$historyContent->id, 'public');

            $type = FileType::where('name', 'image')->get();

            $historyContent->historyFiles()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ])->historyFileType()->sync($type[0]->id);

        }

    }
}
