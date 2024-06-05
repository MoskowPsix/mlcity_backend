<?php

namespace App\Http\Controllers\Api;

use App\Filters\HistoryContent\HistoryContentAuthor;
use App\Filters\HistoryContent\HistoryContentStatuses;
use App\Filters\HistoryContent\HistoryContentStatusesLast;
use App\Filters\HistoryContent\HistoryContentGeoPositionAreaHistoryPlace;
use App\Filters\Event\EventOrderByDateCreate;
use App\Filters\Event\EventDate;
use App\Filters\Event\EventName;
use App\Filters\Event\EventSearchText;
use App\Filters\Event\EventSponsor;
use App\Filters\HistoryContent\HistoryContentEventTypes;
use App\Filters\HistoryContent\HistoryContentSightTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryContent\GetHistoryContentRequest;
use App\Http\Requests\HistoryContent\GetLimitPageRequest;
use App\Mail\HistoryContentCanceled;
use App\Mail\HistoryContentChanged;
use App\Models\Event;
use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\HistoryPlace;
use App\Models\HistorySeance;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\Status;
use App\Models\User;
use App\Services\EventHistoryContentService;
use App\Services\SightHistoryContentService;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Info;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
                        EventOrderByDateCreate::class,
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
        #получаем данные для статуса и дальнейших манипуляций
        $this->checkAccessToCreateHistoryContent();
        $data = $request->toArray();

        $data['history_content']["user_id"] = auth("api")->user()->id;
        $status_id = Status::where("name", "На редактировании")->first()->id;
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
        $data = $request->toArray();
        $status = $request["status"];
        $historyContentId = $request["historyContent"]['id'];

        if ($status == "Опубликовано")
        {
            $historyContent = HistoryContent::find($historyContentId);
            $historyParent = $historyContent->historyContentable;

            #происходит преобразование данных в нужный формат
            $historyRawData = $this->unsetRawHistoryContentData($historyContent->toArray());
            $historyData = $this->notNullData($historyRawData);

            #проверка пустой ли массив
            if(!empty($historyData))
            {
                if(isset($historyData["on_delete"]) && $historyData["on_delete"] == true){
                    $historyParent->delete();
                }
                else{
                    $historyParent->update($historyData);
                }
            }

            #достаем places которые либо будут созданы либо изменены
            $historyPlaces = $historyContent->historyPlaces;

            # Если Place уже существует мы обновляем или удаляем его и(или) его seance
            if(isset($historyPlaces) && count($historyPlaces->toArray())>0){

                foreach($historyPlaces as $historyPlace){

                    $historyPlaceParent = $historyPlace->place;
                    $historyRawData = $this->unsetRawHistoryPlaceData($historyPlace->ToArray());
                    $historyData = $this->notNullData($historyRawData);

                    if(isset($historyPlaceParent)){

                        if ($historyPlace["on_delete"] == true) {
                            $historyPlaceParent->delete();
                        } else {
                            if(!empty($historyData)) {
                                $historyPlaceParent->update($historyData);
                            }

                            $historySeances = $historyPlace->historySeances;

                            if(isset($historySeances) && count($historySeances)>0){

                                foreach($historySeances as $historySeance){


                                    $historyRawData = $this->unsetRawHistorySeanceData($historySeance->toArray());
                                    $historyData = $this->notNullData($historyRawData);

                                    if($historySeance["on_delete"] == true){
                                        $historySeanceParent = $historySeance->seance;
                                        $historySeanceParent->delete();
                                    }
                                    else if(isset($historySeance["seanse_id"])){

                                        if(!empty($historyData)){
                                            $historySeanceParent = $historySeance->seance;
                                            $historySeanceParent->update($historyData);
                                        }

                                    }
                                    else{
                                        $historyPlaceParent->seances()->create($historyData);
                                    }
                                }
                            }
                        }
                        # Если Place не существует мы его добавляем и(или) его seance
                    } else if (!isset($historyPlaceParent)){
                        $place = $historyParent->places()->create($historyData);

                        $historySeances = $historyPlace->historySeances;
                        if(isset($historySeances) && count($historySeances)>0){
                            foreach($historySeances as $historySeance){
                                $historyRawData = $this->unsetRawHistorySeanceData($historySeance->toArray());
                                $historyData = $this->notNullData($historyRawData);


                                if(!empty($historyData)){

                                    $place->seances()->create($historyData);
                                }
                            }
                        }
                    }
                }
            }



            # Находим цены истории и создаем или обновляем их
            $historyPrices = $historyContent->historyPrices;
            if(isset($historyPrices) && count($historyPrices)>0){

                foreach($historyPrices as $historyPrice){

                    $historyPriceParent = $historyPrice->price;
                    $historyRawData = $this->unsetRawHistoryPriceData($historyPrice->toArray());
                    $historyData = $this->notNullData($historyRawData);

                    # Если цена существует мы либо удаляем ее либо обновляем
                    if (isset($historyPriceParent)){
                        if($historyPrice['on_delete'] == true){

                            $historyPriceParent->delete();
                        }
                        else{
                            if (!empty($historyData)){
                                unset($data["price_id"]);
                                $historyPriceParent->update($historyData);
                            }

                        }
                    }
                    # Если цена не существует создаем ее
                    else{
                        $historyParent->prices()->create($historyData);
                    }
                }
            }


            if($historyContent->history_contentable_type=="App\Models\Sight"){
                $historyTypes = $historyContent->historySightTypes;
                foreach($historyTypes as $historyType){
                    $typeId = $historyType["pivot"]["history_contentable_id"];

                    if(isset($historyType["pivot"]['on_delete']) && $historyType["pivot"]['on_delete']==true){
                        $historyParent->types()->detach($typeId);
                    }
                    else{
                        $historyParent->types()->attach($typeId);
                    }
                }
            }
            else if ($historyContent->history_contentable_type=="App\Models\Event"){
                $historyTypes = $historyContent->historyEventTypes;

                foreach($historyTypes as $historyType){
                    $typeId = $historyType["pivot"]["history_contentable_id"];

                    if(isset($historyType["pivot"]['on_delete']) && $historyType["pivot"]['on_delete']==true){
                        $historyParent->types()->detach($typeId);
                    }
                    else{

                        $historyParent->types()->attach($typeId);
                    }
                }
            }

            $historyFiles = $historyContent->historyFiles;

            if(isset($historyFiles)){
                $data = $historyFiles->toArray();


                foreach ($data as $content){

                    if(isset($content['on_delete']) && $content['on_delete']==true){

                        $historyParent->files()->where('id',$content['file_id'])->delete();
                    }
                    else{
                        $path = $content['link'];
                        $filename = basename($path);

                        $historyParent->files()->create([
                            "link" => $path,
                            "local" => 1,
                            "name" => $filename
                        ])->file_types()->attach(1);
                    }
                }



            }
            $historyContent->historyContentStatuses()->create([
                "status_id" => 1
            ]);

            $userFavoriteEmails = $historyParent->favoritesUsers->pluck("email")->toArray();
            $this->sendNotificationAboutChanges($userFavoriteEmails, $historyParent->name);

            return response()->json(["status"=>"success"],201);
        }

        else if ($status == "Отказ")
        {
            $historyContent = HistoryContent::find($historyContentId);
            $description = $request["description"];
            $historyContent->historyContentStatuses()->create([
                "status_id" => 2,
                "descriptions" => $description,
            ]);
            $data = ["description"=> $description, "eventName" => $historyContent->name];

            $user = User::find($historyContent->user_id);
            Mail::to($user->email)->send(new HistoryContentCanceled($data));

            return response()->json(["status"=>"success"],201);
        }
    }


    private function saveLocalFilesImg($historyContent, $file){
        $filename = uniqid('img_');

        $path = $file->store('history_content/'.$historyContent->id, 'public');

        $type = FileType::where('name', 'image')->get();

        $historyFile = $historyContent->historyFiles()->create([
            'name'  => $filename,
            'link'  => '/storage/'.$path,
            'local' => 1
        ]);
        $historyType = $historyFile->historyFileType()->attach($type[0]->id);
    }
    private function saveLocalSightFilesImg($sight, $file){


        $filename = uniqid('img_');

        $path = $file->store('sight/'.$sight->id, 'public');

        $type = FileType::where('name', 'image')->get();

        $sight->files()->create([
            'name'  => $filename,
            'link'  => '/storage/'.$path,
            'local' => 1
        ])->file_types()->attach($type[0]->id);

    }

    private function sendNotificationAboutChanges($emails, $data){
        for ($i = 0; $i<count($emails); $i++){
            Mail::to($emails[$i])->send(new HistoryContentChanged($data));
        }
    }

    private function prepareHistoryPlaceData($data){
        if(isset($data["location"])){
            $data["location_id"] = $data['location']["location_id"];

        }

        unset($data['history_seances']);
        unset($data["event_id"]);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['location']);
        return $data;
    }

    private function unsetRawHistoryContentData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['id']);
        unset($data[ 'history_contentable_id']);
        unset($data[ 'history_contentable_type']);
        unset($data['history_contentable']);

        return $data;
    }

    private function checkAccessToCreateHistoryContent(){
        if(request("type") == "Event")
        {
            if(!((auth('api')->user()->role[0]->name == "root" || auth('api')->user()->role[0]->name == "Admin") || (Event::find(request('id'))->author->id == auth('api')->user()->id))) {
                return response()->json(["status"=>"error", "message" => "access denied" ],403);
            }
        }
        else
         {
            if(!((auth('api')->user()->role[0]->name == "root" || auth('api')->user()->role[0]->name == "Admin") || (Sight::find(request('id'))->author->id == auth('api')->user()->id))) {
                return response()->json(["status"=>"error", "message" => "access denied" ],403);
            }
        }
    }

    private function unsetRawHistoryPlaceData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data["history_content_id"]);
        unset($data["place"]);
        unset($data["place_id"]);
        unset($data['id']);

        return $data;
    }

    public function unsetRawHistorySeanceData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_place_id']);
        unset($data['id']);
        unset($data['seance']);
        unset($data["seance_id"]);

        return $data;
    }

    public function unsetRawSeanseData($seanceData){
        $data = $seanceData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_place_id']);
        unset($data['id']);
        unset($data['place_id']);

        return $data;
    }

    public function unsetRawHistoryPriceData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_content_id']);
        unset($data['id']);


        return $data;
    }

    public function notNullData($data){
        $historyData = [];

        foreach($data as $key=>$data){
            if(!empty($data)){
                    $historyData[$key] = $data;
            }
        }

        return $historyData;
    }


}
