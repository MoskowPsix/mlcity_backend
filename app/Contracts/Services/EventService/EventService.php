<?php

namespace App\Contracts\Services\EventService;

use App\Models\Event;
use App\Models\Location;
use App\Models\Timezone;
use App\Contracts\Services\FileService\FileService;
use App\Models\Organization;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventService implements EventServiceInterface
{
    public function __construct(private readonly FileService $fileService)
    {

    }

    public function store($data): Event
    {
        DB::beginTransaction();
        $user = auth('api')->user();
        try {
            if (!$this->checkUserHaveOrganization()) {
                $organizationId = Organization::create([
                    "name" => $user->name,
                    "user_id" => $user->id,
                    "descriptions" => ""
                ])->id;
            }

            if (!isset($data->organization_id)) {
                $organizationId = Organization::where('user_id', $user->id)->get()->first()->id;
            } else {
                $organizationId = $data->organization_id;
            }

            if (!$this->isUserOrganization($user->id, $organizationId)) {
                throw new Exception("Is not user organization");
            }

            $event = Event::create([
                'name'          => $data->name,
                'sponsor'       => $data->sponsor,
                'description'   => $data->description,
                'price'         => $data->price,
                'materials'     => $data->materials,
                'date_start'    => $data->dateStart,
                'date_end'      => $data->dateEnd,
                'user_id'       => $user->id,
                'organization_id' => $organizationId,
                'vk_group_id'   => $data->vkGroupId,
                'vk_post_id'    => $data->vkPostId,
            ]);
            // Устанавливаем цену
            foreach ($data->prices as $price){
                if($price["cost_rub"] == ""){
                    $event->price()->create([
                        'cost_rub' => 0,
                        'descriptions' => $price['descriptions']
                    ]);
                }
                else{
                    $event->price()->create([
                        'cost_rub' => $price['cost_rub'],
                        'descriptions' => $price['descriptions']
                    ]);
                }
            }
            // Устанавливаем марки
            foreach ($data->places as $place){
                $coords = explode(',',$place['coords']);
                $latitude   = $coords[0]; // широта
                $longitude  = $coords[1]; // долгота
                $timezone_id = Timezone::where('name', Location::find($place['locationId'])->time_zone)->first()->id;
                $place_cr = $event->places()->create([
                    'sight_id' => $place['sightId'],
                    'location_id' => $place['locationId'],
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'address' => $place['address'],
                    'timezone_id' => $timezone_id
                ]);
                // Устанавливаем сеансы марок

                foreach($place['seances'] as $seance) {
                   $place_cr->seances()->create([
                        'date_start' => $seance['dateStart'],
                        'date_end' => $seance['dateEnd']
                    ]);

                }
            }

            $types = explode(",",$data->type[0]);
            $event->types()->sync($types);
            $event->statuses()->attach($data->status, ['last' => true]);
            $event->likes()->create();


            if ($data->vkFilesImg){
                $this->fileService->saveVkFilesImg($event, $data->vkFilesImg);
            }

            if ($data->vkFilesVideo){
                $this->fileService->saveVkFilesVideo($event, $data->vkFilesVideo);
            }
            if ($data->vkFilesLink){
                $this->fileService->saveVkFilesLink($event, $data->vkFilesLink);
            }

            if ($data->localFilesImg){
                $this->fileService->saveLocalFilesImg($event, $data->localFilesImg);
            }

            DB::commit();

            return $event;

    } catch(Exception $e) {
        DB::rollBack();
        Log::error($e);
        throw $e;
    }
    }

    public function checkUserHaveOrganization(): bool {
        $org = Organization::where("user_id", auth('api')->user()->id)->get();
        if (count($org) == 0) {
            return false;
        }

        return true;
    }

    public function isUserOrganization(int $userId, $organizationId): bool {
        $org = Organization::where("user_id", $userId, "id", $organizationId)->isExists();

        if (count($org) == 0) {
            return false;
        }

        return true;
    }
}
