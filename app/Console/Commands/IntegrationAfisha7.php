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
use App\Models\Status;
use App\Models\Timezone;

class IntegrationAfisha7 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'int {type?} {location?} {offset?} {types?}';

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
            case 'all':
                $this->integrationEventsAndSights();
                break;
            case 'event':
                $this->integrationEventsForLocation();
                break;
            case 'sight':
                $this->integrationSightsForLocation();
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
    public function integrationEventsAndSights(): void
    {
        info( 'Start all' );
        $progress = 0;
        $this->setToken(); 
        $this->setLocations();
        $this->setTypes();

        foreach ($this->locations as $location) {
            $progress++;
            if ($location->level == 3) {
                foreach($this->types as $type) {
                    $sights = get_object_vars($this->getSights($location->url, 1, 0, $type->id));
                    if (isset($sights['total'])){
                        $this->startCommand((int)$sights['total'], $location->url, 'sight', $type->id);
                    }
                }
                $events = get_object_vars($this->getEvents($location->id));
                if (isset($events['total'])){
                    $this->startCommand((int)$events['total'], $location->id, 'event', $type->id);
                }
                info( ' | ' .$progress . ' / ' . count($this->locations) . ' | ' );
            }
        }
    }
    /**
     *
     * @return void
     */
    public function integrationEvents(): void
    {
        info( 'Start event' );
        $progress = 0;
        $this->setToken(); 
        $this->setLocations();

        foreach ($this->locations as $location) {
            $progress++;
            if ($location->level == 3) {
                $events = get_object_vars($this->getEvents($location->id));
                if (isset($events['total'])){
                    $this->startCommand((int)$events['total'], (int)$location->id, 'event', '');
                }
                info( ' | ' .$progress . ' / ' . count($this->locations) . ' | ' );
            }
        }
    }

    /**
     *
     * @return void
     */
    public function integrationSights(): void
    {
        info( 'Start sight' );
        $progress = 0;
        $this->setToken(); 
        $this->setLocations();
        $this->setTypes();

        foreach ($this->locations as $location) {
            $progress++;
            if ($location->level == 3) {
                foreach($this->types as $type) {
                    $sights = get_object_vars($this->getSights($location->url, 1, 0, $type->id));
                    if (isset($sights['total'])){
                        $this->startCommand((int)$sights['total'], $location->url, 'sight', $type->id);
                    }
                }
                info( ' | ' .$progress . ' / ' . count($this->locations) . ' | ' ); 
            }
        }
    }
    /**
    *
    * @return void 
    */
    private function startCommand(Int $total,Mixed $location_id, String $type, Int $typeS): void
    {
        $offset = 0;
        try
        {
            while ($total >= 0) {
                $numberOfProcess = 10;
                for ($i = 0; $i < $numberOfProcess; $i++) // Запускаем 10 команд по загрузке sight ['php', 'artisan', 'institutes_save', $page, $limit]
                {
                    if ($total >= 0) {
                        $process = new Process(['php', 'artisan', 'int', $type, $location_id, $offset, $typeS]); 
                        // info('php artisan int ' . $type . ' ' . $location_id . ' ' .$offset . ' ' .$typeS); // проверка вводимой команды
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
            return json_decode('');
        }  
    }
        /**
    *
    * @return  object 
    */
    private function getSights(String $location_url, Int $limit = 1, Int $offset = 0, Int $types_id):  object
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://api.afisha7.ru/v3.1/places/', [
                'form_params' => [
                    'token' => $this->token,
                    'loc_url' => $location_url,
                    "cat_id" => $types_id,
                    'limit' => $limit,
                    'offset' => $offset
                ]
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            Log::error('Ошибка при получении событий');
            return json_decode('');
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
                if (!Event::where('afisha7_id', $event->id)->exists()){
                    $event = $this->getEvent($event->id, $this->location);
                    $event_cr = $this->saveEvent($event);
                    $this->setTypesEvent($event->cat_id, $event_cr);
                    $this->setPrices($event, $event_cr);
                    $this->saveFilesEvent($event->logo, $event_cr);
                    $this->setPlaces($event, $event_cr);
                    $this->setStatusEvent($event_cr);
                }
            }
        }
    }
    /**
     *
     * @return void
     */
    private function integrationSightsForLocation(): void
    {
        $this->argument('location') ? $this->location = $this->argument('location') : $this->failed('not valid argument location');
        $this->argument('types') ? $types_id = $this->argument('types') : $this->failed('not valid argument location');
        $this->setToken();
        $this->setTypes();
        $sights = $this->getSights($this->location, $this->limit, $this->offset, $types_id);
        if(isset($sights->places)) {
            foreach ($sights->places as $sight) {
                if (!Sight::where('afisha7_id', $sight->id)->exists()){
                    $sight_cr = $this->saveSight($sight);
                    $this->saveFilesSight($sight, $sight_cr);
                    $this->setTypesSight($types_id, $sight_cr);
                    $this->setStatusSight($sight_cr);
                }
            }
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
            return json_decode('');
        }     
    }
    /**
     *
     * @return Event
     */
    private function saveEvent(Object $event): Event
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
     * @return Sight
     */
    private function saveSight(Object $sight): Sight
    {
        $location = $this->searchLocationByCoords($sight->latitude,$sight->longitude, $sight->address);
        return Sight::create([
            'name' => $sight->name,
            'sponsor' => 'afisha7.ru',
            'latitude' => $sight->latitude,
            'longitude' => $sight->longitude,
            'location_id' => $location->id,
            'address' => $sight->address,
            'description' => $sight->name,
            'user_id' => 1,
            'afisha7_id' => $sight->id,
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
        $type->exists() ? $event_create->types()->attach($type->first()->id) : null; // решить проблему с типами
    }
     /**
     *
     * @return void
     */
    private function setTypesSight(Int $types_id ,Sight $sight_create): void
    {
        // $type_index = array_search(["id" => (string)$cat_id], $this->types);
        $type_index = array_search($types_id,array_column($this->types, 'id'));
        $type_name = $this->types[$type_index]->name;
        $type = SightType::where('name', $type_name);
        $type->exists() ? $sight_create->types()->attach($type->first()->id) : null;  // Распределить типы (Типы мест отличаются от наших)
    }
    /**
     *
     * @return void
     */
    private function setPrices(Object $event, Event $event_create): void
    {
        $event_create->price()->create([
           'cost_rub' => $event->min_price,
           'descriptions' => 'Минимальная цена'
        ]);
        $event_create->price()->create([
            'cost_rub' => $event->min_price,
            'descriptions' => 'Максимальная цена'
         ]);
    }
     /**
     *
     * @return void
     */
    private function saveFilesEvent(String $logo, Event $event_create): void
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
    private function saveFilesSight(Object $sight, Sight $sight_create): void
    {
        if (isset($sight->logo)) {
            $type_id = FileType::where('name', 'image')->first()->id;
            $sight_create->files()->create([
                "name" => $sight->logo,
                "link" => $sight->logo,
            ])->file_types()->sync($type_id);
        }
    }
     /**
     *
     * @return Location
     */
    private function searchLocationByCoords(Mixed $latitude,Mixed $longitude, String $address): Location
    {
        $radius = 5;
        $location= null;
        if (isset($latitude) && isset($longitude)) {
            while(empty($location) == true) {
                $location = Location::with('locationParent')->whereRaw('(
                    6371 *
                    acos(cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude )))
                ) <= ? ',
                [$latitude, $longitude,  $latitude,  $radius])->first();
                $radius = $radius + 5;
            }
        } else if (isset($address)) {
        $format = 'json';
        $key = env('YANDEX_MAP_API_KEY_SUBGEKT');
        $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$key.'&format='.$format.'&geocode=' . urlencode($address));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($res, true);
            $coords = explode(' ',$res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
            $latitude = $coords[0];
            $longitude = $coords[1];
            while(empty($location) == true) {
                $location = Location::with('locationParent')->whereRaw('(
                    6371 *
                    acos(cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) -
                    radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude )))
                ) <= ? ',
                [$latitude, $longitude,  $latitude,  $radius])->first();
                $radius = $radius + 5;
            }
        } else {
            $location = false;
        }
        return $location;
    }
    /**
     *
     * @return void
     */
    private function setPlaces(Object $event, Event $event_create): void
    {

        foreach ($event->places as $place) {
            $sight_search_name = Sight::where('name', $place->name);
            // $sight_search_address = Sight::where('address', 'ilike' , $place->address);
            // $place_search_address = Place::where('address', 'ilike' , $place->address);
            // switch (true) {
            //     case $sight_search_name->exists():
                    $sight = $sight_search_name->first();
                    $location = $this->searchLocationByCoords($sight->latitude,$sight->longitude, $sight->address);
                    $timezone_id = Timezone::where('UTC', $location->time_zone_utc)->first()->id;
                    $place_create = $event_create->places()->create([
                        'timezone_id' => $timezone_id,
                        'address' => $sight->address,
                        'location_id' => $sight->location_id,
                        'latitude' => $sight->latitude,
                        'longitude' => $sight->longitude,
                        'sight_id' => $sight->id,
                    ]);
                    // break;
                // case $sight_search_address->exists():
                //     $sight = $sight_search_address->first();
                //     $location = $this->searchLocationByCoords($sight->latitude,$sight->longitude, $sight->address);
                //     $timezone_id = Timezone::where('UTC', $location->time_zone_utc)->first()->id;
                //     $place_create = $event_create->places()->create([
                //         'timezone_id' => $timezone_id,
                //         'address' => $sight->address,
                //         'location_id' => $sight->location_id,
                //         'latitude' => $sight->latitude,
                //         'longitude' => $sight->longitude,
                //         'sight_id' => $sight->id,
                //     ]);
                //     break;
                // case $place_search_address:
                //     $place = $place_search_address->first();
                //     $location = $this->searchLocationByCoords($place->latitude,$place->longitude, $place->address);
                //     $timezone_id = Timezone::where('UTC', $location->time_zone_utc)->first()->id;
                //     $place_create = $event_create->places()->create([
                //         'timezone_id' => $timezone_id,
                //         'address' => $place->address,
                //         'location_id' => $place->location_id,
                //         'latitude' => $place->latitude,
                //         'longitude' => $place->longitude,
                //         'sight_id' => $place->id,
                //     ]);
                //    break;
                // default:
                    
                //     break;
            // }
            $this->setSeances($event, $sight, $place_create);
        }
    }
     /**
     *
     * @return void
     */
    private function setStatusSight(Sight $sight): void 
    {
        $status= Status::where('name', 'Опубликовано')->firstOrFail();
        $sight->statuses()->updateExistingPivot( $status, ['last' => false]);
        $sight->statuses()->attach($status, ['last' => true]);
    }
    private function setStatusEvent(Event $event): void 
    {
        $status= Status::where('name', 'Опубликовано')->firstOrFail();
        $event->statuses()->updateExistingPivot( $status, ['last' => false]);
        $event->statuses()->attach($status, ['last' => true]);
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
            return json_decode('');
        }
    }
    /**
     *
     * @return void
     */
    private function setSeances(Object $event, Object $sight, Place $place_create): void
    {
        $seances_types = $this->getSeances($event->id, $event->loc_id);
        foreach ($seances_types as $seances) {
            foreach($seances as $seance) {
                if(isset($seances) && !isset($seances->errors)) {
                    $place_create->seances()->create([
                        'date_start' => gmdate("Y-m-d\TH:i:s\Z", $seance->date_start),
                        'date_end' => gmdate("Y-m-d\TH:i:s\Z", $seance->date_end),
                    ]);
                }
            }
        }
    }
     /**
     *
     * @return array
     */
    private function getSeances(Int $event_id, Int $location_id): array
    {
        try {
            $seances_full = [];
            $types = ['af', 'mk', 'ya', 'rb', 'rf'];
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/seances/';
            foreach($types as $type) {
                $params = [
                    "form_params" => [
                        "token" => $this->token,
                        "id" => $event_id,
                        'loc_id' => $location_id,
                        'type' => $type,
                    ]
                ];    
                $response = $client->request('POST', $url, $params);
                $seances = json_decode($response->getBody()->getContents());
                $seances_full[] = $seances;
            }
            return $seances_full;
        } catch (Exception $e) {
            Log::error('Ошибка при получении мест');
            return json_decode('');
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
