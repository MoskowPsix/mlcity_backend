<?php

namespace App\Listeners\Event;

use App\Events\Event\EventCreated;
use App\Http\Resources\Place\PlaceToHistoryPlaceResource;
use App\Models\HistoryPlace;
use App\Models\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateHistoryContent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EventCreated  $event
     * @return void
     */
    public function handle(EventCreated $event)
    {
        $event = $event->model;
        $data = $this->prepareEventData($event);
        $historyContent = $event->historyContents()->create($data);
        $status_id = Status::where("name", "Опубликовано")->first()->id;
        $historyContent->historyContentStatuses()->create([
            "status_id" => $status_id,
            "descriptions" => "Оригинальный экземпляр"
        ]);
        $places = $event->places;
        // info(count($places->toArray()));
        
        
        foreach($places as $place)
        {   
            $data = $this->preparePlaceData($place);
           $historyPlace = $historyContent->historyPlaces()->create($data);
           
           foreach($place->seances as $seance)
           {
            $data = $this->prepareSeanseData($seance);
            $historySeanse = $historyPlace->historySeances()->create($data);
           }  
        }

        foreach($event->price as $price){
            $data = $this->preparePriceData($price);
            $historyContent->historyPrices()->create($data);
        }

        foreach($event->files as $file){
            $data = $this->prepareFileData($file);
            $historyFile = $historyContent->historyFiles()->create($data);

            foreach($file->file_types as $fileType){
                $data = $fileType->toArray();
                $fileTypeId = $data["pivot"]['type_id'];
                
                unset($data["pivot"]);

                $historyFile->historyFileType()->create([
                    "type_id" => $fileTypeId
                ]);
            }
        }

        foreach($event->types as $type){
            $historyContent->historyEventTypes()->attach($type->id);
            
        }
        
    }

    public function preparePlaceData($place){
        $data = $place->toArray();
        $data["place_id"] = $data["id"];

        unset($data['event_id']);
        unset($data["created_at"]);
        unset($data["updated_at"]);
        unset($data["id"]);

        return $data;
    }

    public function prepareSeanseData($seance){
        $data = $seance->toArray();
        $data["seance_id"] = $data["id"];
        $data["date_start"] = $data["date_start"];
        $data["date_end"] = $data["date_end"];

        unset($data["date_start"]);
        unset($data["date_end"]);
        unset($data["place_id"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);
        unset($data["id"]);

        return $data;
    }

    public function preparePriceData($price){
        $data = $price->toArray();
        $data["price_id"] = $data["id"];

        unset($data["event_id"]);
        unset($data["sight_id"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);
        unset($data["id"]);

        return $data;
        
    }

    public function prepareFileData($file){
        $data = $file->toArray();
        $data["file_id"] = $data["id"];

        unset($data["id"]);
        unset($data["event_id"]);
        unset($data["file_types"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);

        return $data;
    }

    public function prepareEventData($event){
        $data = $event->toArray();
        unset($data["id"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);

        return $data;

    }
}
