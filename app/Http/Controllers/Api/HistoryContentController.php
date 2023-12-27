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
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\Status;
use Illuminate\Console\View\Components\Info;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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

    public function getHistoryContentForIds($id) 
    {
        
        $historyContents = HistoryContent::where('id', $id)->with('user', 'historyEventTypes', 'historySightTypes', 'historyPlaces', 'historyPrices', 'statuses', 'historyFiles');
        return response()->json(["status"=>"success", "historyContents" => $historyContents->firstOrFail()],200);
    }

    public function createHistoryContent(Request $request)
    {
        #получаем данные для статуса и дальнейших манипуляций
        
        
        $data = $request->toArray();
        
        $data['history_content']["user_id"] = auth("api")->user()->id;
        $status_id = Status::where("name", "На модерации")->first()->id;
        
        #определяем тип того что будет создаваться тк id события и достопремечательности может совпадать
        if($data["type"] == "Event") {
            $event = Event::where('id',$data['id'])->first();
            
            $historyContent = $data["history_content"];
            unset($historyContent["history_places"]);
            unset($historyContent['history_prices']);
            unset($historyContent['history_types']);
            unset($historyContent['history_files']);
            info($historyContent);
            $historyContent = $event->historyContents()->create($historyContent);
            $historyContent->historyContentStatuses()->create([
                "status_id" => $status_id
            ]);
            
            #проверяем содержит ли массив places
            if(isset($data["history_content"]["history_places"])){
                
                for($i = 0; $i<count($data["history_content"]["history_places"]); $i++){
                    // info($data["history_content"]["history_places"][$i]);

                    $historyPlace = $this->prepareHistoryPlaceData($data["history_content"]["history_places"][$i]);
                    
                    info($historyPlace);

                    $historyPlace = $historyContent->historyPlaces()->create($historyPlace);

                    #проверяем содержит ли массив seances
                    if (isset($data["history_content"]["history_places"][$i]["history_seances"])){
                        $historySeances = $data["history_content"]["history_places"][$i]["history_seances"]; 
                        foreach($historySeances as $historySeance){
                            $historySeance = $this->unsetRawSeanseData($historySeance);
                            $historySeance = $historyPlace->historySeances()->create($historySeance);
                        }
                        
                    }
                }
                
            }
            #Проверка есть ли цена на изменение или удаление
            
            if(isset($data["history_content"]["history_prices"])){
                $historyPrices = $data["history_content"]["history_prices"];
                
                if(!empty($historyPrices)){
                    for($i = 0; $i<count($historyPrices); $i++){
                        $historyContent->historyPrices()->create($historyPrices[$i]);
                    }
                }
            }

            #Проверка если ли типы на удаление или на добавление
            
            
            if(isset($data["history_content"]["history_types"])){
                $historyTypes = $data["history_content"]["history_types"];
                
                for($i = 0; $i<count($historyTypes); $i++){
                    
                    
                    if(isset($historyTypes[$i]["on_delete"]) &&  $historyTypes[$i]["on_delete"] == true){
                        $historyContent->historyEventTypes()->attach($historyTypes[$i]["id"], ['on_delete'=>true]);
                    }
                    else{
                        $historyContent->historyEventTypes()->attach($historyTypes[$i]["id"]);
                    }
                    
                }
            }

            if(isset($data['history_content']["history_files"])){
                $files = $data['history_files'];
                foreach($files as $file){
                    if(isset($file["on_delete"]) && $file["on_delete"] == true){
                        $historyContent->historyFiles()->create($file);
                    }
                    else{
                        // info($file);
                        $this->saveLocalFilesImg($historyContent, $file);
                    }
                }
                
            }

        }
        else if($data["type"] == "Sight"){
            $sight = Sight::where('id',$data['id'])->first();
            $historyContent = $data['history_content'];

            unset($historyContent['history_prices']);
            unset($historyContent['history_types']);
            unset($historyContent['history_files']);
            info($historyContent);
            $historyContent = $sight->historyContents()->create($historyContent);
            
            

            $historyContent->historyContentStatuses()->create([
                "status_id" => $status_id
            ]);

            #Проверка есть ли цена на изменение или удаление
            if(isset($data["history_content"]["history_prices"])){
                $historyPrices = $data["history_content"]["history_prices"];
                if(!empty($historyPrices)){
                    for($i = 0; $i<count($historyPrices); $i++){
                        $historyContent->historyPrices()->create($historyPrices[$i]);
                    }
                }
            }
            

            #Проверка если ли типы на удаление или на добавление
            if(isset($data["history_content"]["history_types"])){
                $historyTypes = $data["history_content"]["history_types"];
                if(!empty($historyTypes)){

                    for($i = 0; $i<count($historyTypes); $i++){
                        
                        // info($historyTypes[$i]);
                        if(isset($historyTypes[$i]["on_delete"]) &&  $historyTypes[$i]["on_delete"] == true){
                            $historyContent->historySightTypes()->attach($historyTypes[$i]["id"], ['on_delete'=>true]);
                        }
                        else{
                            $historyContent->historySightTypes()->attach($historyTypes[$i]["id"]);
                        }
                        
                    }
                }  
            }
            
            if(isset($data['history_content']["history_files"])){
                $files = $data['history_content']["history_files"];
                info($files);
                for($i = 0; $i<count($files); $i++){
                    $file = $files[$i];
                    if($file instanceof UploadedFile){
                        $this->saveLocalFilesImg($historyContent, $file);
                    }
                    else{
                        info($file);
                        $historyContent->historyFiles()->create($file);
                    }
                }
                
            }
                
            
            
        }
        
        return response()->json(["status"=>"success", "history_content"=>$historyContent->id],201);
    }

    public function acceptHistoryContent(Request $request){
        $data = $request->toArray();
        $status = $request["status"];
        $historyContentId = $request["historyContent"]['id'];

        if ($status == "Опубликованно")
        {
            #достаем историю по id
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
            info($historyPlaces);

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
                                            info($historyData);
                                            $historySeanceParent->update($historyData);
                                        }
                                        
                                    }
                                    else{
                                        info("create seanse to alredy place");
                                        $historyPlaceParent->seances()->create($historyData);
                                    }
                                }
                            }
                        } 
                        # Если Place не существует мы его добавляем и(или) его seance   
                    } else if (!isset($historyPlaceParent)){
                        $place = $historyParent->places()->create($historyData);
                        info($place->id);

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
                                $historyPriceParent->update($historyData);
                            }
                            
                        }
                    }
                    # Если цена не существует создаем ее
                    else if (!isset($historyPriceParent)){
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
            return response()->json(["status"=>"success"],201);
        }

        else if ($status == "Отказ")
        {
            $historyContent = HistoryContent::Find($historyContentId);
            $description = $request["description"];
            $historyContent->historyContentStatuses()->create([
                "status_id" => 2,
                "descriptions" => $description,
            ]);

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
        unset($data["price_id"]);
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
