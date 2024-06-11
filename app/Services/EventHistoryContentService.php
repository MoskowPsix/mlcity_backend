<?php

namespace App\Services;

use App\Models\Event;
use App\Models\FileType;
use App\Models\HistoryContent;
use Carbon\Carbon;


class EventHistoryContentService{
    private HistoryContentService $historyContentService;
    private $historyPlaces;
    private $historyPrices;
    private $historyTypes;
    private $historyFiles;

    public function __construct($historyContent)
    {
        $this->historyContentService = new HistoryContentService();
        if(isset($historyContent["history_places"])){
            $this->historyPlaces = $historyContent["history_places"];
        }
        if(isset($historyContent["history_prices"])){
            $this->historyPrices = $historyContent["history_prices"];
        }
        if(isset($historyContent["history_types"])){
            $this->historyTypes = $historyContent["history_types"];
        }
        if(isset($historyContent["history_files"])){
            $this->historyFiles = $historyContent["history_files"];
        }

    }
    public function storeHistoryContentWithAllData(array $dataForHistoryContent, int $eventId, int $status_id){
        $event = Event::find($eventId);
        $data = $this->historyContentService->getClearHistoryContent($dataForHistoryContent);

        # думаю тут это лишнее, вынести бы потом куда то
        if(isset($data["date_start"])){
            $data["date_start"] =  $this->historyContentService->reformatTheDate($data["date_start"], "Y-m-d H:i:s");
            $data["date_end"] =  $this->historyContentService->reformatTheDate($data["date_end"], "Y-m-d H:i:s");
        }

        $historyContent = $this->historyContentService->createHistoryContent($event, $data, $status_id);
        $this->storeHistoryPlacesWithSeances($historyContent);
        $this->storeHistoryPrices($historyContent);
        $this->storeHistoryTypes($historyContent);
        $this->storeHistoryFiles($historyContent);
        $this->resetOldStatuses($historyContent);
        $historyContent->historyContentable->statuses()->attach($status_id, ["last"=>true]);
        return $historyContent;
    }

    private function resetOldStatuses($historyContent) {
        $statuses = $historyContent->historyContentable->statuses;

        foreach($statuses as $status) {
            $historyContent->historyContentable->statuses()->updateExistingPivot($status["id"], [
                "last" => false
            ]);
        }
    }

    private function storeHistoryPlacesWithSeances($historyContent){
        if($this->historyPlaces){
            foreach($this->historyPlaces as $place){
                $historyPlace = $this->historyContentService->createHistoryPlace($historyContent, $place);


                if(isset($place["history_seances"])){
                    $historySeances = $place["history_seances"];
                    foreach($historySeances as $seance)
                    $this->historyContentService->createHistorySeance($historyPlace, $seance);
                }
            }
        }
    }

    private function storeHistoryPrices($historyContent){
        if($this->historyPrices){
            foreach($this->historyPrices as $price){
                $this->historyContentService->createHistoryPrice($historyContent, $price);
            }
        }
    }

    private function storeHistoryTypes($historyContent){
        if($this->historyTypes){
            foreach($this->historyTypes as $type){
                if(isset($type->on_delete)){
                    $this->historyContentService->createEventHistoryType($historyContent, $type, $type->on_delete);
                }
                else{
                    $this->historyContentService->createEventHistoryType($historyContent, $type);
                }
            }
        }
    }

    private function storeHistoryFiles($historyContent){
        if($this->historyFiles){
            foreach($this->historyFiles as $file){
                if(isset($file['on_delete']) && $file['on_delete'] == true){
                    $file['on_delete'] = true;
                    unset($file['file_types']);
                    $historyFile = $historyContent->historyFiles()->create($file);
                }
                else{
                    $this->historyContentService->saveLocalFilesImg($historyContent, $file);
                }
            }
        }
    }
}
