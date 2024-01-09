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
    protected $signature = 'events_save {page_events?}';

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
        function getMessage($text) {
            try
            {
                file_get_contents('https://api.telegram.org/bot'.env('TELEGRAM_BOT_API').'/sendMessage?chat_id='.env('LOG_CHATS_DOWNLOAD_TELEGRAM').'&text='. $text);
            }  catch (Exception $e) {
                Log::error('Ошибка при отправке сообщения в телеграм: '.json_decode($e));
                sleep(5);
                getMessage($text);
            }            
        }

        function  getPageEvent($page_events, $limit_events) {
            try
            {
                $response = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true));
                return $response;
            }  catch (Exception $e) {
                Log::error('Ошибка при получении страницы events(page='.$page_events.', limit='.$limit_events.'): '.json_decode($e));
                sleep(5);
                getPageEvent($page_events, $limit_events);
            }    
        }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        if($this->argument('page_events') > 1){
            $page_events = (int)$this->argument('page_events');
            print($page_events);
        } else {
            $page_events = 1; 
        }
        $limit_events = 100;
        $total_events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true))->pagination->total;
        $events_download = [];
        $total_events_progress = $total_events / 100;
        $limit_genres = 100;
        $total_genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres, true))->pagination->total;
        $genres_download = [];
        $total_genres_progress = $total_genres / 100;

        $etypesParent = ["Представление", "Показ", "Мероприятие", "Культурные", "Детский показ", "Лекции"];

        $etypesParentRaw = ["performance"=>"Представление", "movie"=>"Показ",
                            "event"=>"Мероприятие", "culture_calendar"=>"Культурные",
                             "children_movie"=>"Детский показ", "lecture"=>"Лекции"];
        $genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres), true);

        date_default_timezone_set('UTC');
        $output->writeln(strtotime('2017-01-10T19:00:00.000Z'));

        $output->writeln('<info>Download start element-2</info>');
        getMessage('Download start element-2');
        $output->writeln('<info>Download step 1: Download genres</info>');
        getMessage('Download step 1: Download genres');
        // while ($total_genres >= 0) {
        //     // Отображение прогресса
        //     $progress = ($total_genres_progress * 100 - $total_genres) / $total_genres_progress;
        //     // $output->writeln((int)$progress . '%');

        //     $genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres.'&page=' . $page_genres, true));
        //     foreach ($genres->items as $genre) {
        //         if (!EventType::where('cult_id', $genre->_id)->first()) {
        //             EventType::create([
        //                 'name' => $genre->title,
        //                 'ico' => 'none',
        //                 'cult_id' => $genre->_id,
        //             ]);
        //         }     
        //     }
        //     $total_genres = $total_genres - 1;
        //     $page_genres = $page_genres + 1;
        // }

        
        //Создание родительских категорий
        foreach($etypesParent as $type){
            EventType::create([
                "name" => $type,
                'ico' => "none"
            ]);
        }

        
        
        //Создание дочерних категорий
        foreach($genres['items'] as $genre){
            
            if (count($genre['types'])>=2){
                if (in_array("performance",$genre['types']) && in_array("movie",$genre['types'])){
                    $etype_id = EventType::where("name","Показ")->first()->id;
                    EventType::create([
                        "name"=>$genre["title"],
                        'ico' => "none",
                        "etype_id" => $etype_id,
                        "cult_id" => $genre['_id']
                    ]);
                }
                elseif(in_array("children_movie",$genre['types']) && in_array("culture_calendar",$genre['types'])){
                    $etype_id = EventType::where("name","Культурные")->first()->id;
                    EventType::create([
                        "name"=>$genre["title"],
                        'ico' => "none",
                        "etype_id" => $etype_id,
                        "cult_id" => $genre['_id']
                    ]);
                }
            }
            else{
                $etype_id = EventType::where("name",$etypesParentRaw[$genre["types"][0]])->first()->id;
                    EventType::create([
                        "name"=>$genre["title"],
                        'ico' => "none",
                        "etype_id" => $etype_id,
                        "cult_id" => $genre['_id']
                    ]);
            } 
        }

        getMessage('Download step 1: Download genres complete!!!');
        
        $type = FileType::where('name', 'image')->firstOrFail();
        $status= Status::where('name', 'Опубликовано')->firstOrFail();
        $output->writeln('<info>Download step 2: Download events</info>');
        getMessage('Download step 2: Download events start');
        while ($total_events >= 0) {
            // Начало отсчёта времени выполнения
            $start_timer = microtime(true);
            
            // Отображение прогресса мест
            $progress = ($total_events_progress * 100 - $total_events) / $total_events_progress;
            //$output->writeln((int)$progress . '%');

            // Запрашиваем страницу ивентов 
            // $events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events.'&statuses=published', true));
            try
            {
            $events = getPageEvent($page_events,$limit_events);

            // Разбираем полученный массив
            foreach ($events->items as $event) {
                //$event = json_decode(file_get_contents('https://www.culture.ru/api/events/' . $event->_id, true));
                // $output->writeln($event->_id);
                if (!Event::where('cult_id', $event->_id)->first()) {
                    //$event_one = json_decode(file_get_contents('https://www.culture.ru/api/events/' .  $event->_id . '?fields=thumbnailFile', TRUE));
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

                    //$output->writeln('<info>'.$event_cr.'</info>');
                    foreach ($event->places as $place) {
                        
                        $timezone = Timezone::where("name",$place->locale->timezone)->first()->id;
                        
                        if (isset($place->institute) && Location::where('cult_id', $place->locale->_id)->first()) {
                            
                            if (Sight::where('cult_id', $place->institute->_id)->first()) {
                                $place_cr =  new Place;
                                $place_cr->event_id = $event_cr->id;
                                $place_cr->address = $place->address;
                                $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                                $place_cr->latitude = $place->location->coordinates[1];
                                $place_cr->longitude = $place->location->coordinates[0];
                                $place_cr->sight_id = Sight::where('cult_id', $place->institute->_id)->first()->id;
                                $place_cr->timezone_id = $timezone;
                                $place_cr->save();
                            } else {
                                $place_cr =  new Place;
                                $place_cr->event_id = $event_cr->id;
                                $place_cr->address = $place->address;
                                $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                                $place_cr->latitude = $place->location->coordinates[1];
                                $place_cr->longitude = $place->location->coordinates[0];
                                $place_cr->timezone_id = $timezone;
                                $place_cr->save();
                            }
                        } else {
                            $place_cr =  new Place;
                            $place_cr->event_id = $event_cr->id;
                            $place_cr->address = $place->address;
                            $place_cr->location_id = Location::where('cult_id', $place->locale->_id)->first()->id;
                            $place_cr->latitude = $place->location->coordinates[1];
                            $place_cr->longitude = $place->location->coordinates[0];
                            $place_cr->timezone_id = $timezone;
                            $place_cr->save();
                        }
                         foreach ($place->seances as $seance) {
                            
                            Seance::create([
                                'place_id'  => $place_cr->id,
                                'date_start' => $seance->startDate,
                                'date_end' => $seance->endDate
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

                    // event(new EventCreated($event_cr));
                }
            }
            }  catch (Exception $e) {
                Log::error('Ошибка при отправке сообщения в телеграм: '.json_decode($e));
                sleep(5);
                // getMessage($e);
            } 
            $total_events = $total_events - 1;
            $page_events = $page_events + 1;

            // Подсчёт времени до конца  
            $end_time = (microtime(true) - $start_timer)  * $total_events / 60;
            $output->writeln((int)$progress.'approximate end time: ' . (int)$end_time . 'min');
            getMessage('page: '.$page_events-1 .', '. (int)$progress . '% approximate end time: ' . (int)$end_time . 'min');
        }

        $output->writeln("<info>Errors: </info>" . $events_download); 
        getMessage('End and complete!!!');
        return print_r('Download element-2 end!');
    }
}