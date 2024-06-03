<?php

namespace App\Services;

use App\Models\HistoryContent;
use App\Models\Sight;

class SightHistoryContentService{
    private HistoryContentService $historyContentService;
    private $historyPrices;
    private $historyTypes;
    private $historyFiles;
    private $historyContent;

    public function __construct($historyContent)
    {
        $this->historyContentService = new HistoryContentService();
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

    public function storeHistoryContentWithAllData(array $dataForHistoryContent, int $sightId, int $status_id){
        $sight = Sight::find($sightId);
        $data = $this->historyContentService->getClearHistoryContent($dataForHistoryContent);

        if(isset($data["date_start"])){
            $data["date_start"] =  $this->historyContentService->reformatTheDate($data["date_start"], "Y-m-d H:i:s");
            $data["date_end"] =  $this->historyContentService->reformatTheDate($data["date_end"], "Y-m-d H:i:s");
        }

        $this->historyContent = $this->historyContentService->createHistoryContent($sight, $data, $status_id);
        $this->storeHistoryPrices();
        $this->storeHistoryTypes();
        $this->storeHistoryFiles();

        $this->resetOldStatuses($historyContent);
        $this->historyContent->historyContentable->statuses()->attach($status_id, ["last"=>true]);

        return $this->historyContent;
    }

    private function resetOldStatuses($historyContent) {
        $statuses = $historyContent->historyContentable->statuses;

        foreach($statuses as $status) {
            $historyContent->historyContentable->statuses()->updateExistingPivot($status["id"], [
                "last" => false
            ]);
        }
    }

    private function storeHistoryPrices(){
        if($this->historyPrices){
            foreach($this->historyPrices as $price){
                $this->historyContentService->createHistoryPrice($this->historyContent, $price);
            }
        }
    }

    private function storeHistoryTypes(){
        if($this->historyTypes){
            foreach($this->historyTypes as $type){
                if(isset($type["on_delete"])){
                    $this->historyContentService->createSightHistoryType($this->historyContent, $type, $type["on_delete"]);
                }
                else{
                    $this->historyContentService->createSightHistoryType($this->historyContent, $type);
                }
            }
        }
    }

    private function storeHistoryFiles(){
        if($this->historyFiles){
            foreach($this->historyFiles as $file){
                if(isset($file["on_delete"]) && $file["on_delete"] == true){
                    $this->historyContent->historyFiles()->create($file);
                }
                else{
                    $this->historyContentService->saveLocalFilesImg($this->historyContent, $file);
                }
            }
        }
    }


}
