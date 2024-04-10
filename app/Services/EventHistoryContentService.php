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

    public function __construct($places, $prices, $types, $files)
    {
        $this->historyPlaces = $places;
        $this->historyPrices = $prices;
        $this->historyTypes = $types;
        $this->historyFiles = $files;
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
                $this->historyContentService->createEventHistoryType($historyContent, $type, $type["on_delete"]);
            }
        }
    }
}
