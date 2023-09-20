<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
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
        // function searchLevelLocales($max_level) {
        //     $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        //     $request = json_decode(file_get_contents('https://www.culture.ru/api/locales?level='.$max_level.'&limit=1', TRUE));
        //     if ($request->total === 0) {
        //         //$output->writeln('<info>' . $max_level . '</info>');
        //         //echo $max_level - 1;
        //         $max_level = $max_level - 1;
        //         //return (int)$max_level;
        //     } else if ($request->total !== 0) {
        //         //print_r($max_level);
        //         $max_level = $max_level + 1;
        //         searchLevelLocales($max_level);
        //     }
        //     return (int)$max_level;
        // }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $page_events = 1;
        $limit_events = 100;
        $total_events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events, true))->pagination->total;
        $events_download = [];
        $total_events_progress = $total_events / 100;

        $limit_genres = 10;
        $page_genres= 1;
        $total_genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres.'&page=' . $page_genres, true))->pagination->total;
        $genres_download = [];
        $total_genres_progress = $total_genres / 100;

        $total = 10;
        // date_default_timezone_set('UTC');
        // $output->writeln(strtotime('2017-01-10T19:00:00.000Z'));

        $output->writeln('<info>Download start element-2</info>');
        $output->writeln('<info>Download step 1: Download genres</info>');

        // while ($total_genres >= 0) {
        //     // Отображение прогресса
        //     $progress = ($total_genres_progress * 100 - $total_genres) / $total_genres_progress;
        //     $output->writeln((int)$progress . '%');

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
            $events = json_decode(file_get_contents('https://www.culture.ru/api/events?page='.$page_events.'&limit='.$limit_events, true));

            // Разбираем полученный массив
            foreach ($events->items as $event) {
                //date_default_timezone_set('UTC');
                $output->writeln($event->_id);
                //echo $event->endDate;
                if (!Event::where('cult_id', $event->_id)->first() && (strtotime($event->endDate) >= time())) {
                    foreach ($event->places as $place) {

                    }



                    $event_one = json_decode(file_get_contents('https://www.culture.ru/api/events/' .  $event->_id . '?fields=thumbnailFile', TRUE));
                    // Подвязываем первое фото
                    if ($event_one) {
                        Event::where('cult_id', $event->_id)->first()->files()->create([
                            "name" => $event->thumbnailFile->originalName,
                            "link" => 'https://cdn.culture.ru/images/'.$event_one->thumbnailFile->publicId.'/w_'.$event_one->thumbnailFile->width.',h_'.$event_one->thumbnailFile->height.'/'.$event_one->thumbnailFile->originalName,
                        ])->file_types()->sync($type->id);
                    } else {
                        $events_download[] = ['id' => $event->_id, 'error' => 'No photo'];
                    }
                
                // Берём тип и ставим тип
                    foreach ($event->genres as $genre) {
                        $types_id = EventType::where('cult_id', $genre->_id)->firstOrFail()->id;
                        // Ставим тип
                        Event::where('cult_id', $event->_id)->first()->types()->attach($types_id);
                    }
                    // Ставим статус
                    Event::where('cult_id', $event->_id)->firstOrFail()->statuses()->updateExistingPivot( $status, ['last' => false]);
                    Event::where('cult_id', $event->_id)->firstOrFail()->statuses()->attach($status, ['last' => true]);
                }
            }
            $total_events = $total_events - 1;
            $page_events = $page_events + 1;

            // Подсчёт времени до конца  
            $end_time = (microtime(true) - $start_timer)  * $total_events / 60;
            $output->writeln('approximate end time: ' . (int)$end_time . 'min');
            $output->writeln(date_default_timezone_get());
        }

        $output->writeln("<info>Errors: </info>" . $events_download); 
        return print_r('Download element-2 end!');
    }
}