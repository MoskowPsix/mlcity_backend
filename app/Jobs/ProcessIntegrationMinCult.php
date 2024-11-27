<?php

namespace App\Jobs;

use App\Contracts\Services\CurrentType\CurrentType;
use App\Contracts\Services\IntegrationMinCult\IntegrationMinCult;
use App\Contracts\Services\IntegrationMinCult\IntegrationMinCultInterface;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Place;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Timezone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessIntegrationMinCult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private readonly IntegrationMinCultInterface $integrationMinCult;
    private int $offset;
    private int $limit;
    /**
     * Create a new job instance.
     */
    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->integrationMinCult = app(IntegrationMinCult::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $events = $this->integrationMinCult->getEvents($this->limit, $this->offset,);
        foreach ($events as $event) {
            if (Event::where('min_cult_id', $event->data->general->id)->exists()) {
                continue;
            }
            DB::beginTransaction();
            try {
                $this->startInt($event);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                continue;
            }
        }
    }
    private function startInt($event): void
    {
        try {
            $org = $this->orgCreate($event);
            $event_cr = $this->eventCreate($event, $org->id);
            $event = $event->data->general;

            $this->saveType($event->category, $event_cr);

            isset($event->price) ? $this->savePrice($event->price, $event_cr) : null;
            isset($event->maxPrice) ? $this->savePrice($event->maxPrice, $event_cr) : null;
            isset($event->image) ? $this->saveFiles($event->image, $event_cr) : null;

            if (isset($event->gallery)) {
                foreach ($event->gallery as $file) {
                    $this->saveFiles($file, $event_cr);
                }
            }

            foreach ($event->places as $place) {
                $this->savePlace($event->seances, $place, $event_cr);
            }

            $this->setStatus($event_cr);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function orgCreate(object $event): Organization
    {
        $place = $event->data->general->places[0];
        $sight = Sight::where('name', $place->name)->where('address', $place->address->fullAddress);
        if($sight->exists()){
            $this->saveType($event->data->general->category, $sight->first());
            return $sight->first()->organization;
        } else {
            $location = $this->getLocation($place->address->mapPosition->coordinates[0], $place->address->mapPosition->coordinates[1]);
            $sight = Sight::create([
                "name" => $place->name,
                "address" => $place->address->fullAddress,
                "latitude" => $place->address->mapPosition->coordinates[0],
                "longitude" => $place->address->mapPosition->coordinates[1],
                "location_id" => $location->id,
                "user_id" => 1,
            ]);
            $this->saveType($event->data->general->category, $sight);
            $status = Status::where('name', 'Опубликовано')->first();
            $sight->statuses()->updateExistingPivot($status, ['last' => false]);
            $sight->statuses()->attach($status->id,  ['last' => true]);
            return $sight->organization()->create();
        }
    }
    private function eventCreate(object $event, int $org_id): Event
    {
        return Event::create([
            'name' => $event->nativeName,
            'description' => $event->data->general->description,
            'materials' => isset($event->data->general->saleLink) ? $event->data->general->saleLink : null,
            'date_start' => Carbon::make($event->data->general->start)->format('Y-m-d H:i:s'),
            'date_end' => Carbon::make($event->data->general->end)->format('Y-m-d H:i:s'),
            'user_id' => 1,
            'min_cult_id' => $this->getId($event),
            'organization_id' => $org_id,
            'source_id' => $event->data->general->id,
            'source_name' => 'min_cult'
        ]);
    }
    private function saveType(Object $type, Event | Sight $event): void
    {
        $current_type = new CurrentType($type->name);
        $type_name = $current_type->getType();
        if(isset($type_name)) {
            $event->types()->attach($type_name['id']);
            $types_ids_cr = $event->types->pluck('id')->toArray();
            $event->types()->detach($types_ids_cr);
            $event->types()->attach(array_unique($types_ids_cr));
        } else {
//            $sight_type = EventType::where('name', $type->name);
//            if(!$sight_type->exists()) {
//                $sight_type = EventType::create(['name' => $type->name, 'ico' => 'none']);
//            }
//            $event->types()->attach($sight_type->id);
        }
    }
    private function savePrice(int $price, Event $event): void
    {
        $event->price()->create([
            'cost_rub' => $price
        ]);
    }
    private function saveFiles(Object $file, Event $event): void
    {
        $file_type = FileType::where('name', 'image')->first();
        $event->files()->create([
            'name' => uniqid('img_'),
            'link' => $file->url,
            'local' => 0,
        ])->file_types()->attach($file_type->id);
    }
    private function savePlace(array $seances, object $place, Event $event): void
    {
        $location = $this->searchLocation($place->address->mapPosition->coordinates);
        $sight = $this->searchSight($place->address->mapPosition->coordinates, $place->address->fullAddress);
        $timezone_id = Timezone::where('UTC', $location->time_zone_utc)->first()->id;
        $place_cr =  $event->places()->create([
            'address' => $place->address->fullAddress,
            'location_id' => $location->id,
            'latitude' => $place->address->mapPosition->coordinates[0],
            'longitude' => $place->address->mapPosition->coordinates[1],
            'timezone_id' => $timezone_id,
        ]);
        !empty($sight) ? $place_cr->update(['sight_id' => $sight->id]) : null;
        foreach ($seances as $seance) {
            $this->saveSeance($seance, $place_cr);
        }
    }
    private function setStatus(Event $event): void
    {
        $status = Status::where('name', 'Опубликовано')->first();
        $event->statuses()->updateExistingPivot($status, ['last' => false]);
        $event->statuses()->attach($status->id,  ['last' => true]);
    }
    private function saveSeance(Object $seance, Place $place): void
    {
        $hours = explode('+', $place->timezones->UTC)[1];
        $seance = $place->seances()->create([
            'date_start' => Carbon::make($seance->start)->addHours($hours)->format('Y-m-d H:i:s'),
            'date_end' => Carbon::make($seance->end)->addHours($hours)->format('Y-m-d H:i:s'),
        ]);
    }
    private function searchLocation(array $coords): Location
    {
        $latitude = $coords[0];
        $longitude = $coords[1];
        $location = Location::select('*')->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$latitude, $longitude, $latitude])
            ->orderBy('distance')->first();
        return $location;
    }
    private function searchSight(array $coords, string $address): Sight | null
    {
        $sight_address = Sight::where('address', 'ILIKE', '%' . $address . '%')->first();
        $sight_coords = Sight::where('latitude', $coords[0])->where('longitude', $coords[1])->first();

        isset($sight_address) ? $sight = $sight_address : $sight = null;
        isset($sight_coords) && !isset($sight_address) ? $sight = $sight_coords : null;

        return $sight;
    }
    public function getId($event)
    {
        return $event->data->general->id;
    }
    private function getLocation(float $lat,float  $long): mixed
    {
        $location = Location::select('*')->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$lat, $long, $lat])
            ->orderBy('distance');
        if (!$location) {
            throw new \Exception('Location not found');
        }
        return $location->first();
    }
}
