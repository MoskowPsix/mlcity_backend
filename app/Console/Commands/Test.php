<?php

namespace App\Console\Commands;

use App\Models\EventType;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $etypesParent = ["Представление", "Показ", "Мероприятие", "Культурные", "Детский показ", "Лекции"];

        $etypesParentRaw = ["performance"=>"Представление", "movie"=>"Показ",
                            "event"=>"Мероприятие", "culture_calendar"=>"Культурные",
                             "children_movie"=>"Детский показ", "lecture"=>"Лекции"];
        $limit_genres = 100;
        $genres = json_decode(file_get_contents('https://www.culture.ru/api/genres?limit='.$limit_genres), true);


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
        
       
        
    }
}
