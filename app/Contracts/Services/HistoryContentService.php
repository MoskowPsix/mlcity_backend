<?php

namespace App\Contracts\Services;

use App\Models\FileType;
use App\Models\HistoryContent;
use App\Models\HistoryPlace;
use App\Models\HistoryPrice;
use App\Models\HistorySeance;
use Carbon\Carbon;

class HistoryContentService {

    public function createHistoryContent($entity, $data, $status_id): HistoryContent {
        $historyContent = $entity->historyContents()->create($data);
        $historyContent->historyContentStatuses()->create([
            "status_id" => $status_id
        ]);

        return $historyContent;
    }

    /**
     * возвращает "чистую" историю без плейсов, цен, типов и тд
    */
    public function getClearHistoryContent($dataForHistoryContent){
        unset($dataForHistoryContent["history_places"]);
        unset($dataForHistoryContent['history_prices']);
        unset($dataForHistoryContent['history_types']);
        unset($dataForHistoryContent['history_files']);

        return $dataForHistoryContent;
    }

    public function createHistoryPlace(HistoryContent $historyContent, $place): HistoryPlace {
        $place = $this->prepareHistoryPlaceData($place);
        info($place);
        $historyPlace = $historyContent->historyPlaces()->create($place);

        return $historyPlace;
    }

    public function createHistorySeance(HistoryPlace $historyPlace, $seance): HistorySeance {
        $dataForCreatingSeance = $this->unsetRawSeanseData($seance);
        $historySeance = $historyPlace->historySeances()->create($dataForCreatingSeance);

        return $historySeance;
    }

    public function createHistoryPrice(HistoryContent $historyContent, $price): HistoryPrice {
        $historyPrice = $historyContent->historyPrices()->create($price);
        return $historyPrice;
    }

    public function createEventHistoryType(HistoryContent $historyContent, $type, bool $onDelete = null){
        $historyTypes = $historyContent->historyEventTypes()->attach($type["id"], ["on_delete" => $onDelete]);
        return $historyTypes;
    }

    public function createSightHistoryType(HistoryContent $historyContent, $type, bool $onDelete = null){
        $historyTypes = $historyContent->historySightTypes()->attach($type["id"], ["on_delete" => $onDelete]);
        return $historyTypes;
    }

    public function createHistoryFile(HistoryContent $historyContent, $file, bool $onDelete){
        $historyContent->historyFiles()->create($file);
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
        unset($data['seances']);
        return $data;
    }

    /**
     * Очистка полей массива сеанса от не нужных полей
    */
    public function unsetRawSeanseData($seanceData){
        $data = $seanceData;
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['history_place_id']);
        unset($data['id']);
        unset($data['place_id']);

        return $data;
    }

    # форматирует дату к переданному формату
    public function reformatTheDate(string $date, string $format){
        return Carbon::parse($date)->format($format);
    }

    public function saveLocalFilesImg($historyContent, $file){
        $filename = uniqid('img_');

        $path = $file->store('history_content/'.$historyContent->id, 'public');

        $type = FileType::where('name', 'image')->get();

        $historyFile = $historyContent->historyFiles()->create([
            'name'  => $filename,
            'link'  => '/storage/'.$path,
            'local' => 1
        ]);
        $historyFile->historyFileType()->attach($type[0]->id);
    }
}
