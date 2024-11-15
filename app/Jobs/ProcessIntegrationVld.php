<?php

namespace App\Jobs;

use App\Contracts\Services\CurrentType\CurrentType;
use App\Contracts\Services\IntegrationVld\IntegrationVldService;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Place;
use App\Models\Sight;
use App\Models\Timezone;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessIntegrationVld implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private string $scroll_id;
    private readonly IntegrationVldService $integrationVld;
    /**
     * Create a new job instance.
     */
    public function __construct(string $scroll_id = null)
    {
        $scroll_id ? $this->scroll_id = $scroll_id : null;
        $this->integrationVld = app(IntegrationVldService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->getContents();
        $this->scroll_id = $response->_scroll_id;
        $this->saveEvent($response);
    }
    private function saveEvent($response)
    {
            foreach ($response->hits->hits as $event) {
                $id = explode('_', $event->_id);
                if (Event::where('source_name', $id[0])->where('source_id', $id[1])->exists()) continue;
                DB::beginTransaction();
                try{
                    $event_form = $this->formEvent($event);
                    $place_form = $this->formPlace($event->_source->venue);

                    $event_cr = Event::create($event_form);
                    isset($place_form) ? $place_cr = $event_cr->places()->create($place_form) : null;
                    if(isset($event->_source->seances)) {
                        $seances_form = $this->formSeance($event->_source->seances, $place_cr);
                        $place_cr->seances()->insert($seances_form);
                    }
                    $event->_source->prices->min > 0 ? $event_cr->prices()->create(['cost_rub' => $event->_source->prices->min]) : null;

                    $event->_source->prices->max > 0 ? $event_cr->prices()->create(['cost_rub' => $event->_source->prices->max]) : null;

                    isset($event->_source->media) ? $this->saveFiles($event->_source->media, $event_cr) : null;
                    $this->setTypes($event->_source->types, $event_cr);
                    DB::commit();
                } catch(Exception $e) {
                    DB::rollBack();
                }
            }
    }

    /**
     * @throws Exception
     */
    private function formEvent(object $event): array
    {
        try {
            $id = explode('_', $event->_id);
            $date_start = explode('+', $event->_source->start_at);
            $date_end = explode('+', $event->_source->end_at);
            if (count($date_start) > 2 || count($date_end) > 2) {
                throw new \Exception('No valid date');
            }
            $org = $this->orgCreate($event->_source->title);
            return [
                'source_id' => $id[1],
                'source_name' => $id[0],
                'name' => $event->_source->title,
                'sponsor' => 'kassir',
                'description' => $event->_source->description,
                'materials' => $event->_source->original_link,
                'date_start' => Carbon::make($date_start[0])->addHour((int)$date_start[1])->toIso8601ZuluString(),
                'date_end' => Carbon::make($date_end[0])->addHour((int)$date_end[1])->toIso8601ZuluString(),
                'user_id' => 1,
                'organization_id' => $org->id,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function orgCreate(string $title): Organization
    {
        $sight = Sight::create([
            "name" => $title,
            "address" => "",
            "description" => "",
            "user_id" => 1,
        ]);
        return $sight->organization()->create();
    }
    private function formPlace(object|null $place): array
    {
        if (empty($place)) {
            throw new \Exception('Place not found');
        }
        $location = $this->getLocation($place->address->location->lat, $place->address->location->long);
        $timezone = $this->getTimezone($location->time_zone);
        $place = [
            'location_id' => $location->id,
            'latitude' => $place->address->location->lat,
            'longitude' => $place->address->location->long,
            'address' => $place->address->address,
            'timezone_id' => $timezone->id,
        ];
        return $place;
    }
    private function formSeance( $seances,Place $place): array
    {
        foreach ($seances as $seance) {
            $seance_form[] = [
                'place_id'      => $place->id,
                'date_start'    => $seance->date_start,
                'date_end'      => $seance->date_end,
            ];
        }
        return $seance_form;
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
    private function getTimezone($time_zone): Timezone
    {
        return Timezone::where('name', $time_zone)->firstOrFail();
    }
    /**
     * @throws Exception
     */
    private function getContents(): object | null
    {
        $this->isScroll() ?
            ($response = $this->integrationVld->getNextScroll($this->scroll_id)) :
            ($response = $this->integrationVld->getFirstScroll());
        if(isset($response)){
            return $response;
        } else throw new Exception('Contents not found');
    }
    private function saveFiles($files, Event $event_cr)
    {
        $image_type = FileType::where('name', 'image')->first();
        foreach($files as $image) {
            $event_cr->files()->create([
                'name' => "img_" . Str::random(16),
                'link' => $image->url,
                'local' => false,
            ])->file_types()->attach($image_type->id);
        }
    }
    private function setTypes(array $types, Event $event)
    {
        $type = $types[0];
        $current_type = new CurrentType($type->name);
        $type_name = $current_type->getType();
        if (isset($type_name)) {
            $event->types()->attach($type_name['id']);
        } else {
            throw  new Exception('Not current type');
        }
    }
    private function isScroll(): bool
    {
        return isset($this->scroll_id);
    }
}
