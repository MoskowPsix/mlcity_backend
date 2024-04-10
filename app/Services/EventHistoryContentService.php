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
    public function storeHistoryContent(array $dataForHistoryContent, int $event_id, int $status_id){
        $event = Event::find($event_id);
        $data = $this->historyContentService->getClearHistoryContent($dataForHistoryContent);

        if(isset($data["date_start"])){
            $data["date_start"] =  $this->historyContentService->reformatTheDate($data["date_start"], "Y-m-d H:i:s");
            $data["date_end"] =  $this->historyContentService->reformatTheDate($data["date_end"], "Y-m-d H:i:s");
        }

        $historyContent = $this->historyContentService->createHistoryContent($event, $data, $status_id);
        $this->storeHistoryPlacesWithSeances($historyContent);
        $this->storeHistoryPrices($historyContent);
        $this->storeHistoryTypes($historyContent);
        $this->storeHistoryFiles($historyContent);

        return $historyContent;
    }

    private function storeHistoryPlacesWithSeances($historyContent){
        if($this->historyPlaces){
            foreach($this->historyPlaces as $place){
                $historyPlace = $this->historyContentService->createHistoryPlace($historyContent, $place);
                $historySeances = $place["history_seances"];

                if(isset($historySeances)){
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
                if(isset($type["on_delete"])){
                    $this->historyContentService->createEventHistoryType($historyContent, $type, $type["on_delete"]);
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
                if(isset($file["on_delete"]) && $file["on_delete"] == true){
                    $historyContent->historyFiles()->create($file);
                }
                else{
                    $this->historyContentService->saveLocalFilesImg($historyContent, $file);
                }
            }
        }
    }
}
