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
    protected $signature = 'sights_save {page_institutes?} {limit_institutes?}';

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
        function  getPageInstitutes($page_institutes, $limit_institutes) {
            try
            {
                $response = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes . '&statuses=published', true));
                return $response;
            }  catch (Exception $e) {
                Log::error('Ошибка при получении страницы events(page='.$page_institutes.', limit='.$limit_institutes.'): '.json_decode($e));
                sleep(5);
                getPageInstitutes($page_institutes, $limit_institutes);
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

        function checkTypeInCurrentTypes($types){
            foreach($types as $type){
                $path = explode("/",$type->path)[0];
                if(SightType::where("cult_path",$path)->exists()){
                    return true;
                }
                break;
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
            }
        }

        function downloadSight($page_institutes, $limit_institutes) {
            $total_institutes = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes . '&statuses=published', true))->pagination->total;
            $institutes_download = [];


            $type = FileType::where('name', 'image')->firstOrFail();
            $status= Status::where('name', 'Опубликовано')->firstOrFail();
            //$status_all = Status::all('id');

            $sights = getPageInstitutes($page_institutes, $limit_institutes);

            foreach ($sights->items as $sight) {
                if (checkTypeInCurrentTypes($sight->rubrics)) {
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
                        $path = explode("/",$rubric->path)[0];
                        $types_id = SightType::where('cult_path', $path);
                        // Ставим тип

                        if($types_id->exists()){
                            // dd($types_id->first()->id);
                            Sight::where('cult_id', $sight->_id)->first()->types()->attach($types_id->first()->id);
                        }

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
                        // event(new SightCreated($sight_cr));
                    }
                    else{
                        // print_r($sight);
                    }
                }

        }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        if($this->argument('page_institutes') > 1){
            $page_institutes = (int)$this->argument('page_institutes');

        }
        else {
            $page_institutes = 1;
        }

        if($this->argument("limit_institutes") >= 1){
            $limit_institutes = (int)$this->argument("limit_institutes");

        }
        else {
            $limit_institutes = 10;
        }

        // $limit_institutes = 100;

        try
        {
            downloadSight($page_institutes, $limit_institutes);
        }
        catch(Exception $e)
        {
            print($e);
            downloadSight($page_institutes, $limit_institutes);
        }

            // Конец исполнения программы


    }
}
