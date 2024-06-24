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
use App\Http\Requests\HistoryContent\GetLimitPageRequest;
use App\Mail\HistoryContentChanged;
use App\Models\Event;
use App\Models\HistoryContent;
use App\Models\Sight;
use App\Models\Status;
use App\Services\DecisionHistoryContentService;
use App\Services\EventHistoryContentService;
use App\Services\SightHistoryContentService;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    public function getHistoryContentForIdsContent($type, $id, GetLimitPageRequest $request)
    {
        $limit = $request->limit && ($request->limit < 50)? $request->limit : 5;
        $page = $request->page;
        if($type == 'sight') {
            $historyContents = Sight::find($id)->historyContents();
            $total = Sight::find($id)->historyContents()->count();
        } elseif ($type == 'event') {
            $historyContents = Event::find($id)->historyContents();
            $total = Event::find($id)->historyContents()->count();
        }
        $response =  $historyContents->orderBy('created_at')->cursorPaginate($limit, ['*'], 'page' , $page);
        return response()->json(["status" => "success", "history_contents" => $response, "total" => $total], 200);
    }

    public function getHistoryContentForIds($id)
    {

        $historyContents = HistoryContent::where('id', $id)->with('user', 'historyEventTypes', 'historySightTypes', 'historyPlaces', 'historyPrices', 'statuses', 'historyFiles');
        return response()->json(["status"=>"success", "historyContents" => $historyContents->firstOrFail()],200);
    }

    public function createHistoryContent(Request $request)
    {
        #Проверяем права пользователя на эту запись
        if ($this->checkAccessToCreateHistoryContent()) {
            return response()->json(["status"=>"error", "message" => "access denied" ],403);
        }
        #Проверяем не находится ли запись уже на редактуре
        if ($this->checkStatuses()) {
            return response()->json(["status"=>"error", "message" => "Your record is already being modified" ],403);
        }
        $data = $request->toArray();

        $data['history_content']["user_id"] = auth("api")->user()->id;
        $status_id = Status::where("name", "Изменено")->first()->id;

        #определяем тип того что будет создаваться тк id события и достопремечательности может совпадать
        if($data["type"] == "Event") {
            $eventHistoryContentService = new EventHistoryContentService($data["history_content"]);
            $historyContent = $eventHistoryContentService->storeHistoryContentWithAllData($data["history_content"], $data["id"], $status_id);
        }
        else if($data["type"] == "Sight"){
            $sightHistoryContentService = new SightHistoryContentService($data["history_content"]);
            $historyContent = $sightHistoryContentService->storeHistoryContentWithAllData($data["history_content"], $data["id"], $status_id);
        }

        return response()->json(["status"=>"success", "history_content"=>$historyContent],201);
    }

    public function acceptHistoryContent(Request $request){
        $status = $request["status"];
        $historyContentId = $request["historyContent"]['id'];
        $decisionHistoryContentService = new DecisionHistoryContentService($historyContentId);

        if ($status == "Опубликовано")
        {
            $decisionHistoryContentService->publishAcceptedHistoryContent();

            $userFavoriteEmails = $decisionHistoryContentService->historyParent->favoritesUsers->pluck("email")->toArray();
            $this->sendNotificationAboutChanges($userFavoriteEmails, $decisionHistoryContentService->historyParent->name);
            return response()->json(["status"=>"success"], 200);
        }

        else if ($status == "Отказ")
        {
            $decisionHistoryContentService->declineHistoryContent($request->get("description"));
            return response()->json(["status"=>"success"], 200);
        }
    }


    private function sendNotificationAboutChanges($emails, $data){
        for ($i = 0; $i<count($emails); $i++){
            Mail::to($emails[$i])->send(new HistoryContentChanged($data));
        }
    }
    private function checkStatuses(){
        $status_id = Status::where('name', 'Изменено')->first()->id;
        info($status_id);
        if(request("type") == "Event"){
            if (Event::where('id', request('id'))->whereHas('statuses', function($q) use ($status_id){
                $q->where('status_id', $status_id)->where('last', true);
            })->exists()) {
                return true;
            } else { 
                return false;
            }
        }
        else if (request("type") == "Sight") {
            if (Sight::find(request('id'))->whereHas('statuses', function($q) use ($status_id){
                $q->where('status_id', $status_id)->where('last', true);
            })->exists()) {
                return true;
            } else { 
                return false;
            }
        }
    }

    private function checkAccessToCreateHistoryContent(){
        if(request("type") == "Event")
        {
            if(!($this->checkRoleExists() || (Event::find(request('id'))->user_id == auth('api')->user()->id))) {
                return true;
            } else {
                return false;
            }
        }
        else
         {
            if(!$this->checkRoleExists() || (Sight::find(request('id'))->user_id == auth('api')->user()->id)) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function checkRoleExists(){
        if (count(auth('api')->user()->role) !== 0) {
            return (auth('api')->user()->role[0]->name == "root" || auth('api')->user()->role[0]->name == "Admin");
        } else { 
            return false;
        }
        
    }
}
