<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\Status;
use Illuminate\Console\Command;

class addEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events_save';

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
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $page_events = 4;
        $limit_events = 10;
        $total_events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true))->pagination->total;
        $events_download = [];
        $total_events_progress = $total_events / 100;

        $limit_genres = 10;
        $page_genres= 1;
        $total_genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres.'&page=' . $page_genres, true))->pagination->total;
        $genres_download = [];
        $total_genres_progress = $total_genres / 100;

        $total = 10;
        date_default_timezone_set('UTC');
        $output->writeln(strtotime('2017-01-10T19:00:00.000Z'));

        $output->writeln('<info>Download start element-2</info>');
        $output->writeln('<info>Download step 1: Download genres</info>');

        while ($total_genres >= 0) {
            // Отображение прогресса
            $progress = ($total_genres_progress * 100 - $total_genres) / $total_genres_progress;
            $output->writeln((int)$progress . '%');

            $genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres.'&page=' . $page_genres, true));
            foreach ($genres->items as $genre) {
                if (!EventType::where('cult_id', $genre->_id)->first()) {
                    EventType::create([
                        'name' => $genre->title,
                        'ico' => 'none',
                        'cult_id' => $genre->_id,
                    ]);
                }     
            }
            $total_genres = $total_genres - 1;
            $page_genres = $page_genres + 1;
        }
        
        $type = FileType::where('name', 'image')->firstOrFail();
        $status= Status::where('name', 'Опубликовано')->firstOrFail();
        $output->writeln('<info>Download step 2: Download events</info>');
        while ($total_events >= 0) {
            // Начало отсчёта времени выполнения
            $start_timer = microtime(true);
            
            // Отображение прогресса мест
            $progress = ($total_events_progress * 100 - $total_events) / $total_events_progress;
            $output->writeln((int)$progress . '%');

            // Запрашиваем страницу ивентов 
            $events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true));

            // Разбираем полученный массив
            foreach ($events->items as $event) {
                //$event = json_decode(file_get_contents('https://www.culture.ru/api/events/' . $event->_id, true));
                $output->writeln($event->_id);
                if (!Event::where('cult_id', $event->_id)->first()) {
                    //$event_one = json_decode(file_get_contents('https://www.culture.ru/api/events/' .  $event->_id . '?fields=thumbnailFile', TRUE));
                    if (str_contains($event->text,'[HTML]')) {
                        $descriptions = rtrim(rtrim(strip_tags($event->text), '[/HTML]'), '[HTML]');
                    } else {
                        $descriptions = strip_tags($event->text);
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

                    //$output->writeln('<info>'.$event_cr.'</info>');
                    foreach ($event->places as $place) {
                        if (isset($place->institute)) {
                            if (Event::where('cult_id', $place->institute->_id)->first()) {
                                $place_cr =  new Place;
                                $place_cr->event_id = $event_cr->id;
                                $place_cr->address = $place->address;
                                $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                                $place_cr->latitude = $place->location->coordinates[1];
                                $place_cr->longitude = $place->location->coordinates[0];
                                $place_cr->sight_id = Event::where('cult_id', $place->institute->_id)->first()->id;
                                $place_cr->save();
                            } else {
                                $place_cr =  new Place;
                                $place_cr->event_id = $event_cr->id;
                                $place_cr->address = $place->address;
                                $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                                $place_cr->latitude = $place->location->coordinates[1];
                                $place_cr->longitude = $place->location->coordinates[0];
                                $place_cr->save();
                            }
                        } else {
                            $place_cr =  new Place;
                            $place_cr->event_id = $event_cr->id;
                            $place_cr->address = $place->address;
                            $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                            $place_cr->latitude = $place->location->coordinates[1];
                            $place_cr->longitude = $place->location->coordinates[0];
                            $place_cr->save();
                        }
                         foreach ($place->seances as $seance) {
                            Seance::create([
                                'place_id'  => $place_cr->id,
                                'dateStart' => gmdate("Y-m-d\TH:i:s\Z", strtotime($seance->startDate) + $seance->startTime),
                                'dateEnd' => gmdate("Y-m-d\TH:i:s\Z", strtotime($seance->endDate) + $seance->endTime)
                            ]);
                         }
                    } 
                    foreach ($event->genres as $genre) {
                        $types_id = EventType::where('cult_id', $genre->_id)->firstOrFail()->id;
                        // Ставим тип
                        Event::where('id', $event_cr->id)->first()->types()->attach($types_id);
                    }
                    if (isset($event->thumbnailFile)) {
                        Event::where('id', $event_cr->id)->first()->files()->create([
                            "name" => $event->thumbnailFile->originalName,
                            "link" => 'https://cdn.culture.ru/images/'.$event->thumbnailFile->publicId.'/w_'.$event->thumbnailFile->width.',h_'.$event->thumbnailFile->height.'/'.$event->thumbnailFile->originalName,
                        ])->file_types()->sync($type->id);
                    } 
                    Event::where('id', $event_cr->id)->firstOrFail()->statuses()->updateExistingPivot( $status, ['last' => false]);
                    Event::where('id', $event_cr->id)->firstOrFail()->statuses()->attach($status, ['last' => true]); 
                }
            }
            $total_events = $total_events - 1;
            $page_events = $page_events + 1;

            // Подсчёт времени до конца  
            $end_time = (microtime(true) - $start_timer)  * $total_events / 60;
            $output->writeln('approximate end time: ' . (int)$end_time . 'min');
        }

        $output->writeln("<info>Errors: </info>" . $events_download); 
        return print_r('Download element-2 end!');
    }
}