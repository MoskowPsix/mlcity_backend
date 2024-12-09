<?php

namespace App\Contracts\Services;

use App\Mail\HistoryContentCanceled;
use App\Models\HistoryContent;
use App\Models\Location;
use App\Models\Place;
use App\Models\Seance;
use App\Models\Status;
use App\Models\Timezone;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class DecisionHistoryContentService {
    private $historyContent;
    public $historyParent;

    public function __construct(int $historyContentId)
    {
        $this->historyContent = HistoryContent::find($historyContentId);
        $this->historyParent = $this->historyContent->historyContentable;
    }

    public function publishAcceptedHistoryContent() {
        $publishedStatusId = Status::where("name", "Опубликовано")->get()->first()->id;
        // Изменение основных полей.
        $this->changeHistoryContent();

        // Измнение мест и сеансов.
        $this->changeHistoryContentPlacesAndSeances();

        // Измнение цен.
        $this->changeHistoryContentPrices();

        // Измнение типов.
        $this->changeHistoryContentTypes();

        // Измнение файлов.
        $this->changeHistoryFiles();

        // Установка того кто принял эту редактуру.
        $this->setAccepter();

        // Сброс значения last=true у старых статусов.
        $this->resetOldStatuses();

        // Установка нового статуса.
        $this->historyContent->historyContentable->statuses()->attach($publishedStatusId, ["last"=>true]);
        $this->historyContent->historyContentStatuses()->create([
            "status_id" => Status::where("name", "Опубликовано")->get()[0]->id
        ]);

    }

    /**
     * Функция которая сбрасывает значение last у родителя истории.
     */
    private function resetOldStatuses() {
        $statuses = $this->historyContent->historyContentable->statuses;

        foreach($statuses as $status) {
            $this->historyContent->historyContentable->statuses()->updateExistingPivot($status["id"], [
                "last" => false
            ]);
        }
    }

    /**
     * Функция отмены, выставляет статус у истории в отменено и отправляет оповещение пользователю.
     * @param string $description сообщение почему изменения были отклонены.
     */
    public function declineHistoryContent($description) {
        $description = $description;
            $this->historyContent->historyContentStatuses()->create([
                "status_id" => 2,
                "descriptions" => $description,
            ]);
            $data = ["description"=> $description, "eventName" => $this->historyContent->name];

            $user = User::find($this->historyContent->user_id);
            Mail::to($user->email)->send(new HistoryContentCanceled($data));
    }

    /**
     * Изменение или удаление родителя истории.
     */
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

    /**
     * Измнение оригинальных мест и сеансов.
     */
    public function changeHistoryContentPlacesAndSeances(){
        $historyPlaces = $this->historyContent->historyPlaces;

        if(count($historyPlaces) > 0){
            foreach($historyPlaces as $historyPlace){
                $parentOfHistoryPlace = $historyPlace->place;
                $historyData = $this->unsetUnusableHistoryPlaceData($historyPlace->toArray());
                $historyData = $this->getNotNullData($historyData);
                if(isset($parentOfHistoryPlace)){
                    $this->deleteEntityIfNeed($parentOfHistoryPlace, $historyPlace);
                    $this->updateEntityIfNeed($parentOfHistoryPlace, $historyData);
                } else {
                    // Если родителя не существовало значит запись идет на создание.
                    $parentOfHistoryPlace = $this->createPlaceIfNeed($this->historyParent, $historyPlace, $historyData);
                }
                $this->changeHistoryContentSeances($historyPlace, $parentOfHistoryPlace);
            }
        }
    }


    /**
     * Изменение оригинальных сеансов.
     */
    public function changeHistoryContentSeances($historyPlace, $parentOfHistoryPlace) {
        $historySeances = $historyPlace->historySeances;

        if(!$this->dataIsEmpty($historySeances)) {
            foreach($historySeances as $historySeance) {
                $historySeanceParent = $historySeance->seance;
                $historyData = $this->unsetUnusableHistorySeanceData($historySeance->toArray());
                $historyData = $this->getNotNullData($historyData);

                if(isset($historySeanceParent)){
                    $this->deleteEntityIfNeed($historySeanceParent, $historyData);
                    $this->updateEntityIfNeed($historySeanceParent, $historyData);
                } else{
                    // Если родителя не существовало значит запись идет на создание.
                    $this->createSeanceIfNeed($parentOfHistoryPlace, $historySeance, $historyData);
                }
            }
        }
    }

    /**
     * Изменение оригинальных цен.
     */
    public function changeHistoryContentPrices() {
        $historyPrices = $this->historyContent->historyPrices;

        if(!$this->dataIsEmpty($historyPrices)) {
            foreach($historyPrices as $historyPrice) {
                $historyPriceParent = $historyPrice->price;
                $historyData = $this->unsetUnusableHistoryPriceData($historyPrice->toArray());
                $historyData = $this->getNotNullData($historyData);
                if(isset($historyPriceParent)){
                    $this->deleteEntityIfNeed($historyPriceParent, $historyData);
                    $this->updateEntityIfNeed($historyPriceParent, $historyData);
                } else {
                    $this->createPriceIfNeed($this->historyParent, $historyPrice, $historyData);
                }
            }
        }
    }

    /**
     * Изменение оригинальных типов.
     */
    public function changeHistoryContentTypes() {
        if($this->historyContent->history_contentable_type == "App\Models\Sight"){
            $historyTypes = $this->historyContent->historySightTypes;
        } else {
            $historyTypes = $this->historyContent->historyEventTypes;
        }
        foreach($historyTypes as $historyType){
            $typeId = $historyType["pivot"]["history_contentable_id"];
            if(isset($historyType["pivot"]['on_delete']) && $historyType["pivot"]['on_delete']==true){
                $this->historyParent->types()->detach($typeId);
            } else{
                $this->historyParent->types()->attach($typeId);
                $types = $this->historyParent->types->pluck('id')->toArray();
                $this->historyParent->types()->detach($types);
                $this->historyParent->types()->attach(array_unique($types));
            }
        }
    }

    /**
     * Изменение оригинальных файлов, либо удаление, либо добавление новых.
     */
    public function changeHistoryFiles() {
        $historyFiles = $this->historyContent->historyFiles;
        if(!$this->dataIsEmpty($historyFiles)) {
            foreach($historyFiles as $historyFile) {
                if(isset($historyFile['on_delete']) && $historyFile['on_delete']==true){
                    $this->historyParent->files()->where('id',$historyFile['file_id'])->delete();
                }
                else{
                    $path = $historyFile['link'];
                    $filename = basename($path);

                    $this->historyParent->files()->create([
                        "link" => $path,
                        "local" => 1,
                        "name" => $filename
                    ])->file_types()->attach(1);
                }
            }
        }
    }

    public function createPlaceIfNeed($parent,$historyPlace, $historyData): Place {
        if(!isset($histroyPlace["place_id"])){
            $location = Location::find($historyPlace["location_id"]);

            $timeZoneId = Timezone::where("name", $location->time_zone)->get()[0]->id;

            $historyData["timezone_id"] = $timeZoneId;
            return $parent->places()->create($historyData);
        }
    }

    public function createSeanceIfNeed($parent, $historySeance, $historyData) {
        if(!isset($historySeance["seance_id"]))
        $parent->seances()->create($historyData);
    }

    public function createPriceIfNeed($parent, $historyPrice, $historyData) {
        if(!isset($historyPrice["price_id"])) {
            if($this->historyContent->history_contentable_type == "App/Models/Sight"){
                $parent->prices()->create($historyData);
            }
            else {
                $parent->prices()->create($historyData);
            }
        }
    }

    /**
     * Функция удаляет любую сущность если 2-ым аргументом в массиве есть истинное свойство **on_delete**.
    */
    public function deleteEntityIfNeed($parent, $data) {
        if(isset($data['on_delete']) && $data['on_delete'] == true) {
            $parent->delete();
        }
    }

    /**
     * Функция обновления любой сущности.
     * @param array $historyData данные которые будут обновлены.
     */
    public function updateEntityIfNeed($parent, $historyData) {
        if(!$this->dataIsEmpty($historyData)){
            $parent->update($historyData);
        }
    }

    /**
     * Функция удаляет из массива данные которые не должны попасть в массив при обновлении родителя.
     */
    private function unsetUnusableHistoryContentData(array $historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['id']);
        unset($data[ 'history_contentable_id']);
        unset($data[ 'history_contentable_type']);
        unset($data['history_contentable']);

        return $data;
    }

    /**
     * Функция удаляет из массива данные которые не должны попасть в массив при обновлении родителя места.
     */
    private function unsetUnusableHistoryPlaceData(array $historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data["history_content_id"]);
        unset($data["place"]);
        unset($data["place_id"]);
        unset($data['id']);

        return $data;
    }

    /**
     * Функция удаляет из массива данные которые не должны попасть в массив при обновлении родителя цены.
     */
    public function unsetUnusableHistoryPriceData(array $historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_content_id']);
        unset($data['id']);


        return $data;
    }

    /**
     * Функция удаляет из массива данные которые не должны попасть в массив при обновлении сеанса.
     */
    public function unsetUnusableHistorySeanceData($historyRawData){
        $data = $historyRawData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_place_id']);
        unset($data['id']);
        unset($data['seance']);
        unset($data["seance_id"]);

        return $data;
    }

    /**
     * Функция которая уставливает кто принял эти изменения.
     */
    private function setAccepter() {
        $this->historyContent->update([
            "accepter_id" => auth('api')->user()->id ?? auth('moonshine')->user()->id
        ]);
    }

    /**
     * Функция которая извлекает из массива данные которые не являются _null_.
     */
    private function getNotNullData(array $data){
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
