<?php

namespace App\Console\Commands;

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        function checkTypeInCurrentTypes($genres){
            foreach($genres as $genre){
                if(EventType::where("cult_id",$genre->_id)->exists()){
                    return true;
                }
                break;
            }
        }
        function  getPageEvent($page_events, $limit_events) {
            try
            {
                $type = FileType::where('name', 'image')->firstOrFail();
                $status= Status::where('name', 'Опубликовано')->firstOrFail();
                $events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true));
                foreach ($events->items as $event) {
                    if (!Event::where('cult_id', $event->_id)->first() && checkTypeInCurrentTypes($event->genres)) {
                        if (str_contains($event->text,'[HTML]')) {
                            $descriptions =  strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $event->text));
                        } else {
                            $descriptions =  strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $event->text));
                        }
                        $event_cr = new Event;
                        $event_cr->name = $event->title;
                        $event_cr->sponsor = $event->organizations[0]->name;
                        $event_cr->description = $descriptions;
                        $event_cr->materials = $event->saleLink;
                        $event_cr->date_start = $event->startDate;
                        $event_cr->date_end = $event->endDate;
                        $event_cr->user_id = 1;
                        $event_cr->cult_id = $event->_id;
                        $event_cr->save();

                        if (isset($event->price)){
                        if (($event->price->min === 0) && ($event->price->max === 0)) {
                            $price = new Price;
                            $price->event_id = $event_cr->id;
                            $price->cost_rub = 0;
                            $price->descriptions = 'Бесплатный вход.';
                            $price->save();
                        }else if ($event->price->min === 0) {
                            $price = new Price;
                            $price->event_id = $event_cr->id;
                            $price->cost_rub = 0;
                            $price->descriptions = 'Возможен бесплатный вход.';
                            $price->save();

                            $price = new Price;
                            $price->event_id = $event_cr->id;
                            $price->cost_rub = $event->price->max;
                            $price->descriptions = 'Самая дорогая цена.';
                            $price->save();

                        }else if ($event->price->min === $event->price->max) {
                                $price = new Price;
                                $price->event_id = $event_cr->id;
                                $price->cost_rub = $event->price->min;
                                $price->descriptions = 'Одна цена на все билеты.';
                                $price->save();
                            } else {
                                $price = new Price;
                                $price->event_id = $event_cr->id;
                                $price->cost_rub = $event->price->min;
                                $price->descriptions = 'Самый низкая цена.';
                                $price->save();

                                $price = new Price;
                                $price->event_id = $event_cr->id;
                                $price->cost_rub = $event->price->max;
                                $price->descriptions = 'Самая дорогая цена.';
                                $price->save();
                            }
                        }
                        
                        foreach ($event->places as $place) {

                            $timezone = Timezone::where("name", $place->locale->timezone)->first()->id;

                            if (isset($place->institute)) {
                                $sight = Sight::where('cult_id', $place->institute->_id)->first();
                                $sight ? $sight_id = $sight->id : $sight_id = null;

                                $place_cr =  new Place;
                                $place_cr->event_id = $event_cr->id;
                                $place_cr->address = $place->address;
                                $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                                $place_cr->latitude = $place->location->coordinates[1];
                                $place_cr->longitude = $place->location->coordinates[0];
                                $place_cr->sight_id = $sight_id;
                                $place_cr->timezone_id = $timezone;
                                $place_cr->save();
                                foreach ($place->seances as $seance) {
                                    Seance::create([
                                        'place_id'  => $place_cr->id,
                                        'date_start' => $seance->startDate,
                                        'date_end' => $seance->endDate
                                    ]);
                                }
                            }
                        }
                        foreach ($event->genres as $genre) {
                            $types_id = EventType::where('cult_id', $genre->_id);
                            if($types_id->exists()){
                                Event::find($event_cr->id)->types()->attach($types_id->first()->id);
                            }
                        }
                        if (isset($event->thumbnailFile)) {
                            if (preg_match('/[a-z]+/i',$event->thumbnailFile->publicId)) {
                            Event::find($event_cr->id)->files()->create([
                                "name" => $event->thumbnailFile->originalName,
                                "link" => 'https://cdn.culture.ru/images/'.$event->thumbnailFile->publicId.'/w_'.$event->thumbnailFile->width.',h_'.$event->thumbnailFile->height.'/'.$event->thumbnailFile->originalName,
                            ])->file_types()->sync($type->id);
                            } else {
                                info($event_cr->id);
                                Event::find($event_cr->id)->files()->create([
                                    "name" => $event->thumbnailFile->originalName,
                                    "link" => 'https://cdn.culture.ru/c/'. $event->thumbnailFile->publicId .'.'. $event->thumbnailFile->width .'x'. $event->thumbnailFile->height .'.'.$event->thumbnailFile->format,
                                ])->file_types()->sync($type->id);
                            }
                        }
                        Event::find($event_cr->id)->statuses()->updateExistingPivot( $status, ['last' => false]);
                        Event::find($event_cr->id)->statuses()->attach($status, ['last' => true]);

                    }
                }
            }  catch (Exception $e) {
                Log::error('Ошибка при получении страницы events(page='.$page_events.', limit='.$limit_events.'): '.$e);
                sleep(5);
                getPageEvent($page_events, $limit_events);
            }
        }

        if($this->argument('page_events') > 1){
            $page_events = (int)$this->argument('page_events');

        }
        else {
            $page_events = 1;
        }

        if($this->argument("limit_events") >= 1){
            $limit_events = (int)$this->argument("limit_events");

        }
        else {
            $limit_events = 10;
        }


        getPageEvent($page_events,$limit_events);


        return 0;
    }
}
