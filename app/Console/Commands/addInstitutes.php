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


        function checkTypeInCurrentTypes($types){
            foreach($types as $type){
                $path = explode("/",$type->path)[0];
                if(SightType::where("cult_path",$path)->exists()){
                    return true;
                }
                break;
            }
        }



        function downloadSight($page_institutes, $limit_institutes) {
            $type = FileType::where('name', 'image')->firstOrFail();
            $status= Status::where('name', 'Опубликовано')->firstOrFail();


            $sights = getPageInstitutes($page_institutes, $limit_institutes);

            foreach ($sights->items as $sight) {
                if (checkTypeInCurrentTypes($sight->rubrics) && !Sight::where('cult_id', $sight->_id)->first()) {
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

                    }

                    // Берём тип и ставим тип
                    foreach ($sight->rubrics as $rubric) {
                        $path = explode("/",$rubric->path)[0];
                        $types_id = SightType::where('cult_path', $path);
                        $culture_type = SightType::where("name", "Культура");
                        $museum_type = SightType::where("name", "Музеи");
                        // Ставим тип

                        if($types_id->exists()){
                            Sight::where('cult_id', $sight->_id)->first()->types()->attach($types_id->first()->id);
                        }
                        if($path=="theaters"){
                            Sight::where('cult_id', $sight->_id)->first()->types()->attach($culture_type->id);
                        }
                        if($path=="music"){
                            Sight::where('cult_id', $sight->_id)->first()->types()->attach($culture_type->id);
                        }
                        if($path=="literature"){
                            Sight::where('cult_id', $sight->_id)->first()->types()->attach($museum_type->id);
                        }

                    }
                    // Подвязываем фото
                    if (isset($sight->thumbnailFile)) {
                        if (preg_match('/[a-z]+/i',$sight->thumbnailFile->publicId)) {
                        Sight::find($sight_cr->id)->files()->create([
                            "name" => $sight->thumbnailFile->originalName,
                            "link" => 'https://cdn.culture.ru/images/'.$sight->thumbnailFile->publicId.'/w_'.$sight->thumbnailFile->width.',h_'.$sight->thumbnailFile->height.'/'.$sight->thumbnailFile->originalName,
                        ])->file_types()->sync($type->id);
                    } else {
                        Sight::find($sight_cr->id)->files()->create([
                            "name" => $sight->thumbnailFile->originalName,
                            "link" => 'https://cdn.culture.ru/c/'. $sight->thumbnailFile->publicId .'.'. $sight->thumbnailFile->width .'x'. $sight->thumbnailFile->height .'.'.$sight->thumbnailFile->format,
                        ])->file_types()->sync($type->id);
                    }
                    } else {

                    }
                    // Ставим статус
                    Sight::find($sight_cr->id)->statuses()->updateExistingPivot( $status, ['last' => false]);
                    Sight::find($sight_cr->id)->statuses()->attach($status, ['last' => true]);
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
