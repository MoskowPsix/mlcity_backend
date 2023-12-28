<?php

namespace App\Console\Commands;

use App\Events\Sight\SightCreated;
use App\Models\FileType;
use Illuminate\Console\Command;
use App\Models\SightType;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\Log;


class addInstitutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'institutes_save {page_institutes?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get institutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        function searchLevelLocales($max_level) {
            $request = json_decode(file_get_contents('https://www.culture.ru/api/locales?level='.$max_level.'&limit=1', TRUE));
            if ($request->total === 0) {
                $max_level = $max_level - 1;
            } else if ($request->total !== 0) {
                $max_level = $max_level + 1;
                searchLevelLocales($max_level);
            }
            return (int)$max_level;
        }
        function  getPageInstitutes($page_institutes, $limit_institutes) {
            try
            {
                $response = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes . '&statuses=published', true));
                return $response;
            }  catch (Exception $e) {
                Log::error('Ошибка при получении страницы events(page='.$page_institutes.', limit='.$limit_institutes.'): '.json_decode($e));
                sleep(5);
                getPageEvent($page_institutes, $limit_institutes);
            }    
        }
        function getInstitute($id) {
            try
            {
                $response = json_decode(file_get_contents('https://www.culture.ru/api/institutes/' .  $id . '?fields=thumbnailFile', TRUE));
                return $response;
            }  catch (Exception $e) {
                Log::error('Ошибка при получении института '.$id.': '.json_decode($e));
                sleep(5);
                getInstitute($id);
            }    
        }
        function getRubricsPage($limit_rubrics, $page_rubrics) {
            try
            {
                $response = json_decode(file_get_contents('https://www.culture.ru/api/rubrics?sort=level&limit='.$limit_rubrics.'&page=' . $page_rubrics, true));
                return $response;
            }  catch (Exception $e) {
                Log::error('Ошибка при получении страницы рубрик(page='.$limit_rubrics.', limit='.$page_rubrics.'): '.json_decode($e));
                sleep(5);
                getRubricsPage($limit_rubrics, $page_rubrics);
            }    
        }

        function getMessage($text) {
            try
            {
                file_get_contents('https://api.telegram.org/bot'.env('TELEGRAM_BOT_API').'/sendMessage?chat_id='.env('LOG_CHATS_DOWNLOAD_TELEGRAM').'&text='. $text);
            }  catch (Exception $e) {
                Log::error('Ошибка при отправке сообщения в телеграм: '.json_decode($e));
                sleep(5);
                getMessage($text);
            }                  }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        if($this->argument('page_institutes') > 1){
            $page_institutes = (int)$this->argument('page_institutes');
            print($page_institutes);
        } else {
            $page_institutes = 1; 
        }
        $limit_institutes = 100;
        $total_institutes = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes . '&statuses=published', true))->pagination->total;
        $institutes_download = [];
        $total_institutes_progress = $total_institutes / 100;


        $limit_rubrics = 10;
        $page_rubrics = 1;
        $total_rubrics = json_decode(file_get_contents('https://www.culture.ru/api/rubrics?sort=level&limit='.$limit_rubrics.'&page=' . $page_rubrics, true))->pagination->total;
        $rubrics_download = [];
        $total_rubric_progress = $total_rubrics / 100;

        $limit_locations = 10;
        $level_max_locations = searchLevelLocales(1);
        $level_locations = 1;
        

        // $output->writeln('Download start element-1');
        // $output->writeln('Download step 1(max '.$level_max_locations.' level locations): download locations');
        // getMessage('Download start element-1');
        // getMessage('Download step 1(max '.$level_max_locations.' level locations): download locations');
        // $null_location = json_decode(file_get_contents('https://www.culture.ru/api/locales/1', true));
        // if (!Location::where('cult_id', $null_location->_id)->first()) {
        //     Location::create([
        //         'name' => $null_location->title,
        //         'time_zone' => $null_location->timezone,
        //         'cult_id' => $null_location->_id
        //     ]);
        // }
        // while ($level_max_locations >= $level_locations) {
        //     $page_locations = 1;
        //     $total_locations = json_decode(file_get_contents('https://www.culture.ru/api/locales?limit='.$limit_locations.'&page=' . $page_locations . '&level=' . $level_locations, true))->pagination->total;
        //     $output->writeln('Level '.$level_locations.' locations start:');
        //     getMessage('Level '.$level_locations.' locations start:');
        //     while ($total_locations >= 0) {
        //         $locales = json_decode(file_get_contents('https://www.culture.ru/api/locales?&limit='.$limit_locations.'&page=' . $page_locations . '&level=' . $level_locations, true));
        //         foreach ($locales->items as $local) {
        //             if (!Location::where('cult_id', $local->_id)->first()) {
        //                 print_r($local);
        //                 Location::create([
        //                     'name' => $local->title,
        //                     'time_zone' => $local->timezone,
        //                     'cult_id' => $local->_id,
        //                     'location_id' => Location::where('cult_id', (int)$local->parentId)->firstOrFail()->id,
        //                 ]);
        //                 getMessage('Новая локация'. $local);
        //             } 
        //         }
        //         $total_locations = $total_locations - 1;
        //         $page_locations = $page_locations + 1;
        //     }
        //     $level_locations = $level_locations + 1;
        // }   


        $output->writeln('Download step 2: download rubrics');
        getMessage('Download step 2: download rubrics');
         while ($total_rubrics >= 0) {
            // Отображение прогресса
            $progress = ($total_rubric_progress * 100 - $total_rubrics) / $total_rubric_progress;
            //$output->writeln((int)$progress . '%');
            //getMessage((int)$progress . '%');

            $rubrics = getRubricsPage($limit_rubrics, $page_rubrics);
            foreach ($rubrics->items as $rubric) {
                if (!$rubric->parentId){
                    if(!SightType::where('cult_id', $rubric->_id)->first()) {

                        SightType::create([
                            'name' => $rubric->title,
                            'ico' => 'none',
                            'cult_id' => $rubric->_id,
                        ]);

                    }
                } else {
                    if (SightType::where('cult_id', $rubric->parentId)->first()) {
                        if(!SightType::where('cult_id', $rubric->_id)->first()) {
                            SightType::create([
                                'name' => $rubric->title,
                                'ico' => 'none',
                                'cult_id' => $rubric->_id,
                                'stype_id' => SightType::where('cult_id', $rubric->parentId)->firstOrFail()->id,
                            ]);
                        }
                    } else {
                        $rubrics_download[] = $rubric;
                    }
                }
            }
            $total_rubrics = $total_rubrics - 1;
            $page_rubrics = $page_rubrics + 1;
        }
        $output->writeln('Download step 2: check rubrics');
        getMessage('Download step 2: check rubrics');
        function retrySearch($rubrics) { 
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->write('.'); 
            foreach($rubrics as $rubric) {
                if (SightType::where('cult_id', $rubric->parentId)->first()) {

                    SightType::create([
                        'name' => $rubric->title,
                        'ico' => 'none',
                        'cult_id' => $rubric->_id,
                        'stype_id' => SightType::where('cult_id', $rubric->parentId)->firstOrFail()->id,
                    ]);

                } else {

                    $rubrics_retry[] = $rubric;
                    $rubric_parent = json_decode(file_get_contents('https://www.culture.ru/api/rubrics/' . $rubric->parentId, true));

                    if (!$rubric_parent->parentId) {

                        SightType::create([
                            'name' => $rubric_parent->title,
                            'ico' => 'none',
                            'cult_id' => $rubric_parent->_id,
                        ]);

                    } else {

                        SightType::create([
                            'name' => $rubric_parent->title,
                            'ico' => 'none',
                            'cult_id' => $rubric_parent->_id,
                            'stype_id' => SightType::where('cult_id', $rubric_parent->parentId)->firstOrFail()->id,
                        ]);

                    }
                }
            }
             if (!empty($rubrics_retry)) {
                retrySearch($rubrics_retry);
            }
        }
        retrySearch($rubrics_download);
        $output->writeln('Download step 2: check success');
        getMessage('Download step 2: check success');

        $type = FileType::where('name', 'image')->firstOrFail();
        $status= Status::where('name', 'Опубликовано')->firstOrFail();
        //$status_all = Status::all('id');
        $output->writeln('Download step 3: download sights');
        getMessage('Download step 3: download sights');
        while ($total_institutes >= 0) {

            $start_timer = microtime(true);

            // Отображение прогресса мест
            $progress = ($total_institutes_progress * 100 - $total_institutes) / $total_institutes_progress;
            $output->writeln((int)$progress . '%, page: ' . $page_institutes);

            $sights = getPageInstitutes($page_institutes, $limit_institutes);
            foreach ($sights->items as $sight) {
                if (!Sight::where('cult_id', $sight->_id)->first() && $sight->status !== 'deleted' && Location::where('cult_id', $sight->locale->_id)->first()) {
                    // Берём тип
                    // SightType::where('cult_id', );
                    // Сохраняем место
                    if (isset($sight->locale)) {
                        if( str_contains($sight->text,'[HTML]') ) {
                            
                            $sight_cr = Sight::create([
                                'name'          => $sight->title,
                                'sponsor'       => $sight->passport->organization,
                                'location_id'   => Location::where('cult_id', $sight->locale->_id)->firstOrFail()->id,
                                'address'       => $sight->address,
                                'latitude'      => $sight->location->coordinates[1],
                                'longitude'     => $sight->location->coordinates[0],
                                'description'   => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $sight->text)),
                                'user_id'       => 1,
                                'cult_id'       => $sight->_id,
                                'work_time'     => $sight->workTime,
                            ]);
                        } else {
                            $sight_cr = Sight::create([
                                'name'          => $sight->title,
                                'sponsor'       => $sight->passport->organization,
                                'location_id'   => Location::where('cult_id', $sight->locale->_id)->firstOrFail()->id,
                                'address'       => $sight->address,
                                'latitude'      => $sight->location->coordinates[1],
                                'longitude'     => $sight->location->coordinates[0],
                                'description'   => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $sight->text)),
                                'user_id'       => 1,
                                'cult_id'       => $sight->_id,
                                'work_time'     => $sight->workTime,
                            ]);
                        }
                    } else {
                        if( str_contains($sight->text,'[HTML]') ) {
                            $sight_cr = Sight::create([
                                'name'          => $sight->title,
                                'sponsor'       => $sight->passport->organization,
                                'location_id'   => 1,
                                'address'       => $sight->address,
                                'latitude'      => $sight->location->coordinates[1],
                                'longitude'     => $sight->location->coordinates[0],
                                'description'   => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $sight->text)),
                                'user_id'       => 1,
                                'cult_id'       => $sight->_id,
                                'work_time'     => $sight->workTime,
                            ]);
                        } else {
                            $sight_cr = Sight::create([
                                'name'          => $sight->title,
                                'sponsor'       => $sight->passport->organization,
                                'location_id'   => 1,
                                'address'       => $sight->address,
                                'latitude'      => $sight->location->coordinates[1],
                                'longitude'     => $sight->location->coordinates[0],
                                'description'   => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $sight->text)),
                                'user_id'       => 1,
                                'cult_id'       => $sight->_id,
                                'work_time'     => $sight->workTime,
                            ]);
                        }
                        $institutes_download[] = ['id' => $sight->_id, 'error' => 'No locale'];
                    }

                    // Берём тип и ставим тип
                    foreach ($sight->rubrics as $rubric) {
                        $types_id = SightType::where('cult_id', $rubric->_id)->firstOrFail()->id;
                        // Ставим тип
                        Sight::where('cult_id', $sight->_id)->first()->types()->attach($types_id);
                    }
                    $sight_one = getInstitute($sight->_id);
                    // Подвязываем фото
                    if (isset($sight_one->thumbnailFile)) {
                        Sight::where('cult_id', $sight->_id)->first()->files()->create([
                            "name" => $sight->thumbnailFile->originalName,
                            "link" => 'https://cdn.culture.ru/images/'.$sight_one->thumbnailFile->publicId.'/w_'.$sight_one->thumbnailFile->width.',h_'.$sight_one->thumbnailFile->height.'/'.$sight_one->thumbnailFile->originalName,
                        ])->file_types()->sync($type->id);
                    } else {
                        //$output->writeln($sight_one->_id);
                        $institutes_download[] = ['id' => $sight->_id, 'error' => 'No photo'];
                    }
                    // Ставим статус
                    Sight::where('cult_id', $sight->_id)->firstOrFail()->statuses()->updateExistingPivot( $status, ['last' => false]);
                    Sight::where('cult_id', $sight->_id)->firstOrFail()->statuses()->attach($status, ['last' => true]);
                    event(new SightCreated($sight_cr    ));
                }
            }
            $total_institutes = $total_institutes - 1;
            $page_institutes = $page_institutes + 1;
            // Конец исполнения программы 
            $end_time = (microtime(true) - $start_timer)  * $total_institutes / 60;
            $output->writeln('approximate end time: ' . (int)$end_time . 'min, page: ' . $page_institutes);
            getMessage((int)$progress . '% approximate end time: ' . (int)$end_time . 'min, page: ' . $page_institutes);
        }     
        // $output->writeln("<info>Errors: </info>" . $institutes_download); 
        getMessage('Complate!!!');
        return print_r('Download element-1 end!');
    }
}
