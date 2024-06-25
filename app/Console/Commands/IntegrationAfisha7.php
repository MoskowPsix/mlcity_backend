<?php

namespace App\Console\Commands;

use App\Models\Location;
use Throwable;
use Symfony\Component\Process\Process;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Place;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\Price;
use App\Models\EventType;
use App\Models\SightType;
use App\Models\FileType;


class IntegrationAfisha7 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'int {type?} {location?} {offset?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

     /**
     * @var array
     */
    private $types = []; 

     /**
     * @var string
     */
    private $type = ""; 

    /**
     * @var string
     */
    private $token = ""; 

    /**
     * @var array
     */
    private $locations = [];
    /**
     * @var integer
     */
    private $location;

    /**
     * @var integer
     */
    private $offset = 0;
    /**
     * @var integer
     */
    private $limit = 100;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // $this->setTokenEnv();
        // Переопределяем переменные если пришли аргументы
        $this->argument('offset') ? $this->offset = (int)$this->argument('offset') : null;
        $this->argument('type')? $this->type = $this->argument('type') : $this->type = 'events';
        // Определяем какая функция должна вызываться
        switch ($this->type) {
            case 'events':
                $this->integrationEvents();
                break;
            case 'event':
                $this->integrationEventsForLocation();
                break;
            default:
                $this->error('Argument not found');
                break;
        }

        return Command::SUCCESS;
    }
    /**
     *
     * @return void
     */
    public function integrationEvents(): void
    {
        $progress = 0;
        $this->setToken(); 
        $this->setLocations();
        // $this->setTypes();

        foreach ($this->locations as $location) {
            $progress++;
            if ($location->level == 3) {
                // print($this->token);
                $events = get_object_vars($this->getEvents($location->id));
                if (isset($events['total'])){
                    $this->startCommand((int)$events['total'], (int)$location->id);
                } else {
                    print('тотал не найден location: ' . $location->id);
                }
                print( ' | ' .$progress . ' / ' . count($this->locations) . ' | ' );
            }
        }
    }

    /**
    *
    * @return void 
    */
    private function startCommand(Int $total,Int $location_id): void
    {
        $offset = 0;
        try
        {
            while ($total >= 0) {
                $numberOfProcess = 10;
                for ($i = 0; $i < $numberOfProcess; $i++) // Запускаем 10 команд по загрузке sight ['php', 'artisan', 'institutes_save', $page, $limit]
                {
                    if ($total >= 0) {
                        $process = new Process(['php', 'artisan', 'int', 'event', $location_id, $offset]); 
                        $process->setTimeout(0);
                        $process->disableOutput();
                        $process->start();
                        $processes[] = $process;   
                        $total = $total - $this->limit;
                        $offset = $offset + $this->limit;
                    }
                }
                while (count($processes)) {  
                    foreach ($processes as $i => $runningProcess) {    
                        // этот процесс завершен, поэтому удаляем его
                        if (!$runningProcess->isRunning()) {      
                            unset($processes[$i]);    
                        }   
                        // sleep(1); // Тормозит процесс
                    }
                }
            }

        }
        catch (Throwable $e) {
            Log::error('Ошибка при выполнении функции старта команды');
        }
    }

    /**
     *
     * @return void
     */
    private function setTokenEnv(): void 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/gettoken/';
            
            $params = [
                "form_params" => [
                    "APIKey" => env('AFISHA_7_API_KEY'),
                    "org" => env('AFISHA_7_ORG')
                ]
            ];

            $response = $client->request('POST', $url, $params);
            $token = json_decode($response->getBody()->getContents());
            $this->setNewEnv('AFISHA_7_TOKEN', $token->token);
        } catch (Exception $e) {
            Log::error('Ошибка при токена');
        }   
    }
    /**
     *
     * @return void
     */
    private function setToken(): void
    {
        $this->token = env('AFISHA_7_TOKEN');
    }
    /**
     *
     * @return void
     */
    private function setLocations(): void
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/locales/';
            $params = [
                "form_params" => [
                    "token" => $this->token
                ]
            ];
            $response = $client->request('POST', $url, $params);
            $locations = json_decode($response->getBody()->getContents());
            foreach ($locations->level as $level) {
                foreach($level as $location) {
                    $this->locations[] = $location;
                }
            }
        } catch (Exception $e) {
            Log::error('Ошибка при получении городов');
        }   
    }
    /**
     *
     * @return void
     */
    private function setTypes(): void 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/categories/';
            $params = [
                "form_params" => [
                    "token" => $this->token
                ]
            ];
            $response = $client->request('POST', $url, $params);
            $types = json_decode($response->getBody()->getContents());
            $this->types = $types->categories;
        } catch (Exception $e) {
            Log::error('Ошибка при получении типов');
        }   
    }
    /**
     *
     * @return object
     */
    private function getEvents(Int $location_id, Int $limit = 1, Int $offset = 0): object 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/evs/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "loc_id" => $location_id,
                    "limit" => $limit,
                    "offset" => $offset,
                    "date_end" => date("Y-m-d H:i:s")
                ]
            ];
            $response = $client->request('POST', $url, $params);
            $events = json_decode($response->getBody()->getContents());
            return $events;
        } catch (Exception $e) {
            Log::error('Ошибка при получении событий');
        }  
    }
    /**
     *
     * @return void
     */
    private function integrationEventsForLocation(): void
    {
        $this->argument('location') ? $this->location = $this->argument('location') : $this->failed('not valid argument location');

        $this->setToken();
        $this->setTypes();
        $events = $this->getEvents($this->location, $this->limit, $this->offset);
        if(isset($events->events)) {
            foreach ($events->events as $event) {
                $event = $this->getEvent($event->id, $this->location);
                // $event_cr = $this->saveEvent($event);
                // $this->setTypesEvent($event->cat_id, $event_cr);
                // $this->setPrices($event, $event_cr);
                // $this->setFiles($event->logo, $event_cr);
                $this->setPlaces($event);
                // $this->setStatus($event_cr->id, $event);
            }
        } else {
            info('нет ивентов ' . $this->location);
        }
    }
     /**
     *
     * @return object
     */
    
    private function getEvent(Int $event_id,Int $location_id): object 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/events/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "loc_id" => $location_id,
                    "id" => $event_id
                ]
            ];

            $response = $client->request('POST', $url, $params);
            $event = json_decode($response->getBody()->getContents());

            return $event;
        } catch (Exception $e) {
            Log::error('Ошибка при получении события: event_id' . $event_id . ', location_id'. $location_id);
        }     
    }
     /**
     *
     * @return object
     */
    private function getSeances(Int $place_id, Int $location_id): object
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/evsofplace/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "pl_id" => $place_id,
                    "loc" => $location_id,   
                ]
            ];
            $response = $client->request('POST', $url, $params);
            $seances = json_decode($response->getBody()->getContents());
            return $seances;
        } catch (Exception $e) {
            Log::error('Ошибка при получении событий');
        }
    }
    /**
     *
     * @return Event
     */
    private function saveEvent($event): Event
    {
        return Event::create([
            'name'        => $event->name,
            'sponsor'     => "afisha7.ru",
            'description' => $event->description,
            'date_start'  => gmdate("Y-m-d\TH:i:s\Z", $event->date_start),
            'date_end'    => gmdate("Y-m-d\TH:i:s\Z", $event->date_end),
            'user_id'     => 1,
        ]);
    }
    /**
     *
     * @return void
     */
    private function setTypesEvent(Int $cat_id,Event $event_create): void
    {
        // $type_index = array_search(["id" => (string)$cat_id], $this->types);
        $type_index = array_search($cat_id,array_column($this->types, 'id'));
        $type_name = $this->types[$type_index]->name;
        $type = EventType::where('name', $type_name);
        $type->exists() ? $event_create->types()->attach($type->first()->id) : info('тип не найден');
    }
    /**
     *
     * @return void
     */
    private function setPrices(Object $event, Event $event_create): void
    {
        $event_create->prices()->create([
           'cost_rub' => $event->min_price,
           'descriptions' => 'Минимальная цена'
        ]);
        $event_create->prices()->create([
            'cost_rub' => $event->min_price,
            'descriptions' => 'Максимальная цена'
         ]);
    }
     /**
     *
     * @return void
     */
    private function setFiles(String $logo,Event $event_create): void
    {
        $type_id = FileType::where('name', 'image')->first()->id;
        $event_create->files()->create([
            "name" => $logo,
            "link" => $logo,
        ])->file_types()->sync($type_id);
    }
    /**
     *
     * @return void
     */
    private function setPlaces(Object $event, Event $event_create): void
    {

        foreach ($event->places as $place) {
            $sight_search_name = Sight::where('name', 'ilike' , $place->name);
            $sight_search_address = Sight::where('address', 'ilike' , $place->address);
            $place_search_address = Place::where('address', 'ilike' , $place->address);
            switch (true) {
                case $sight_search_name->exists():
                    $sight = $sight_search_name->first();
                    $place_create = $event_create->places()->create([
                        'address' => $sight->address,
                        'location_id' => $sight->location_id,
                        'latitude' => $sight->latitude,
                        'longitude' => $sight->longitude,
                        'sight_id' => $sight->id,
                    ]);
                    break;
                case $sight_search_address->exists():
                    $sight = $sight_search_address->first();
                    $place_create = $event_create->places()->create([
                        'address' => $sight->address,
                        'location_id' => $sight->location_id,
                        'latitude' => $sight->latitude,
                        'longitude' => $sight->longitude,
                        'sight_id' => $sight->id,
                    ]);
                    break;
                case $place_search_address:
                    $place = $place_search_address->first();
                    $place_create = $event_create->places()->create([
                        'address' => $place->address,
                        'location_id' => $place->location_id,
                        'latitude' => $place->latitude,
                        'longitude' => $place->longitude,
                        'sight_id' => $place->id,
                    ]);
                    break;
                default:
                    
                    break;
            }
            $this->setSeances($place, $place_create);
        }
    }
    /**
     *
     * @return object
     */
    private function getPlaces(Int $event_id, Int $location_id): object
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/places/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "loc_url" => $this->location,
                ]
            ];

            $response = $client->request('POST', $url, $params);
            $places = json_decode($response->getBody()->getContents());

            return $places;
        } catch (Exception $e) {
            Log::error('Ошибка при получении мест');
        }
    }
    /**
     *
     * @return void
     */
    private function setSeances(Object $placePlace, $place_create): void
    {
        $seances = $this->getSeances($placePlace->id, $placePlace->location_id);
        if(isset($seances->seances)) {
            foreach ($seances->seances as $seance) {
                $place_create->seances()->create([
                    'date_start' => gmdate("Y-m-d\TH:i:s\Z", $seance->date_start),
                    'date_end' => gmdate("Y-m-d\TH:i:s\Z", $seance->date_end),
                ]);
            }
        }
    }
    /**
     *
     * @return void
     */
    public function failed($message): void
    {
        $this->error($message);
        dd();
    }
    /**
     *
     * @return void
     */
    private function setNewEnv(String $key,String $value): void
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($value),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}
