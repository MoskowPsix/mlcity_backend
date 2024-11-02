<?php

namespace App\Console\Commands;

use App\Contracts\Services\CurrentType\CurrentType;
use App\Events\Event\EventCreated;
use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Timezone;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class addEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events_save {page_events} {limit_events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get elements-2';

    private int $offset = 0;
    private array $genres;

    private string $url = 'https://www.culture.ru/api-next/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->argument('page_events') > 1 ? $page_events = (int)$this->argument('page_events') : $page_events = 1;

        $this->offset = $page_events;

        $this->argument("limit_events") >= 1 ? $limit_events = (int)$this->argument("limit_events") : $limit_events = 10;
        try {
            $this->getPageEvent($page_events, $limit_events);
        } catch (Exception $e) {
            return 1;
        }
        return 0;
    }
    private function  getPageEvent($page_events, $limit_events, $i = 0): void
    {
        try
        {
            $type = FileType::where('name', 'image')->firstOrFail();
            $status= Status::where('name', 'Опубликовано')->firstOrFail();
            $start = Carbon::now()->toIso8601ZuluString();
            $end = Carbon::now()->addYear(1)->toIso8601ZuluString();
            $events = json_decode(file_get_contents($this->url . "atlas/events?offset=$this->offset&limit=$limit_events&startDateFrom=$start&startDateTo=$end", true));
            if (count($events) == 0) {
                throw new Exception('No events');
            }
            foreach ($events as $event) {
                if (!Event::where('cult_id', $event->_id)->first()) {
                    $event = json_decode(file_get_contents($this->url . 'events/' . $event->_id, true));
                    $event_cr = $this->saveEvent($event);
                    switch (true) {
                        case ($event->priceMin === 0) && ($event->priceMax === 0):
                            $event_cr->prices()->create([
                                'cost_rub' => 0,
                            ]);
                            break;
                        case $event->priceMin === 0:
                            $event_cr->prices()->create([
                                'cost_rub' => 0,
                            ]);
                            $event_cr->prices()->create([
                                'cost_rub' => $event->priceMax,
                            ]);
                            break;
                        case $event->priceMin === $event->priceMax:
                            $event_cr->prices()->create([
                                'cost_rub' => $event->priceMax,
                            ]);
                            break;
                        default:
                            $event_cr->prices()->create([
                                'cost_rub' => $event->priceMin,
                            ]);
                            $event_cr->prices()->create([
                                'cost_rub' => $event->priceMax,
                            ]);
                            break;
                    }

                    foreach ($event->places as $place) {
                        $timezone = Timezone::where("name", $place->locale->timezone)->first()->id;
                        if (isset($place->institute)) {
                            $sight = Sight::where('cult_id', $place->institute->_id)->first();
                            $sight ? $sight_id = $sight->id : $sight_id = null;
                            $event_cr->places()->create([
                                'cult_id'       => $place->_id,
                                'address'       => $place->address,
                                'location_id'   => Location::where('cult_id', $place->locale->_id)->first()->id,
                                'latitude'      => $place->location->coordinates[1],
                                'longitude'     => $place->location->coordinates[0],
                                'sight_id'      => $sight_id,
                                'timezone_id'   => $timezone,
                            ]);
                        }
                    }

                    foreach ($event->seances as $seance){
                        $place_s = Place::where('cult_id', $seance->placeId)->first();
                        if (isset($place_s)) {
                            $place_s->seances()->create([
                                'date_start' => $seance->startDate,
                                'date_end' => $seance->endDate
                            ]);
                        }
                    }

                    foreach ($event->genres as $genre) {
                        $current_type = (new CurrentType($genre->title))->getType();
                        if(isset($current_type['id'])) {
                            $event_cr->types()->attach($current_type['id']);
                        }
                    }

                    if (isset($event->thumbnailFile)) {
                        if (preg_match('/[a-z]+/i',$event->thumbnailFile->publicId)) {
                            $event_cr->files()->create([
                                "name" => $event->thumbnailFile->originalName,
                                "link" => 'https://cdn.culture.ru/images/'.$event->thumbnailFile->publicId.'/w_'.$event->thumbnailFile->width.',h_'.$event->thumbnailFile->height.'/'.$event->thumbnailFile->originalName,
                            ])->file_types()->sync($type->id);
                        } else {
                            $event_cr->files()->create([
                                "name" => $event->thumbnailFile->originalName,
                                "link" => 'https://cdn.culture.ru/c/'. $event->thumbnailFile->publicId .'.'. $event->thumbnailFile->width .'x'. $event->thumbnailFile->height .'.'.$event->thumbnailFile->format,
                            ])->file_types()->sync($type->id);
                        }
                    }
                    $event_cr->statuses()->updateExistingPivot( $status, ['last' => false]);
                    $event_cr->statuses()->attach($status, ['last' => true]);
                }
            }
        }  catch (Exception $e) {
            if ($i < 4) {
                Log::error($e);
                sleep(3);
                $this->getPageEvent($page_events, $limit_events, $i);
            } else {
                Log::error('Ошибка при получении страницы events более 3 раз(page=' . $page_events . ', offset=' . $limit_events . '): ' . $e->getMessage());
            }
        }
    }
    private function saveEvent(object $event): Event
    {
        return Event::create([
            'name'          => $event->title,
            'sponsor'       => 'culture.ru',
            'description'   => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $event->text)),
            'materials'     => $event->saleLink,
            'date_start'    => $event->startDate,
            'date_end'      => $event->endDate,
            'user_id'       => 1,
            'cult_id'       => $event->_id,
            'age_limit'     => $event->ageRestriction ?? '',
        ]);
    }
}
