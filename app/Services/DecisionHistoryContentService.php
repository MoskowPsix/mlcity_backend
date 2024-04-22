<?php

use App\Models\HistoryContent;

class DecisionHistoryContentService {
    private $historyContent;
    private $historyParent;

    public function __construct(int $historyContentId)
    {
        $this->historyContent = HistoryContent::find($historyContentId);
        $this->historyParent = $this->historyContent->historyContentable;
    }

    public function publishAcceptedHistoryContent(){
        $this->changeHistoryContent();

    }

    public function changeHistoryContent(){
        $historyData = $this->unsetUnusableHistoryContentData($this->historyContent->toArray());
        $notNullHistoryData = $this->getNotNullData($historyData);

        if(!$this->dataIsEmpty($notNullHistoryData))
        {
            if(isset($notNullHistoryData["on_delete"]) && $notNullHistoryData["on_delete"] == true){
                $this->historyParent->delete();
            }
            else{
                $this->historyParent->update($notNullHistoryData);
            }
        }
    }

    public function changeHistoryContentPlaces(){
        $historyPlaces = $this->historyContent->historyPlaces;

        if(!$this->dataIsEmpty($historyPlaces)){
            foreach($historyPlaces as $historyPlace){
                $parentOfHistoryPlace = $historyPlace->place;
                $historyData = $this->unsetUnusableHistoryPlaceData($historyPlace->toArray());
            }
        }
    }

    private function unsetUnusableHistoryContentData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['id']);
        unset($data[ 'history_contentable_id']);
        unset($data[ 'history_contentable_type']);
        unset($data['history_contentable']);

        return $data;
    }

    private function unsetUnusableHistoryPlaceData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data["history_content_id"]);
        unset($data["place"]);
        unset($data["place_id"]);
        unset($data['id']);

        return $data;
    }

    private function getNotNullData($data){
        $historyData = [];

        foreach($data as $key=>$data){
            if(!empty($data)){
                    $historyData[$key] = $data;
            }
        }

        return $historyData;
    }

    private function dataIsEmpty($data){
        return empty($data);
    }
}
