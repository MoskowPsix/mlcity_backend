<?php

namespace App\Console\Commands;

use App\Models\Location;
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


class IntegrationAfisha7 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'int';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string
     */
    private $token = ""; 

    /**
     * @var array
     */
    private $locations = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->getToken(); 
        print($this->token);
        $this->getLocations();

        foreach ($this->locations as $location) {
            $events = $this->getEvents($location);
            foreach ($events as $event) {
                $event_full = $this->getEvent($event, $location);
                $this->saveEvent($event_full);
            }
        }

        return Command::SUCCESS;
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    private function getToken(): void 
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
            
            $this->token = $token->token;
        } catch (Exception $e) {
            Log::error('Ошибка при токена');
        }   
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    private function getLocations(): void
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
     * Execute the console command.
     *
     * @return object
     */
    private function getEvents($location): object 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/evs/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "loc_id" => $location->id,
                    "date_end" => date("Y-m-d H:i:s")
                ]
            ];

            $response = $client->request('POST', $url, $params);
            $events = json_decode($response->getBody()->getContents());
            print_r($events);

            return $events;
        } catch (Exception $e) {
            Log::error('Ошибка при получении событий');
        }  
    }
     /**
     * Execute the console command.
     *
     * @return object
     */
    private function getEvent($event, $location): object 
    {
        try {
            $client = new Client();
            $url = 'https://api.afisha7.ru/v3.1/events/';
            
            $params = [
                "form_params" => [
                    "token" => $this->token,
                    "loc_id" => $location['id'],
                    "id" => $event['id']
                ]
            ];

            $response = $client->request('POST', $url, $params);
            $event = json_decode($response->getBody()->getContents());

            return $event;
        } catch (Exception $e) {
            Log::error('Ошибка при получении события: event_id' . $event->id . ', location_id'. $location->id);
        }     
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    private function saveEvent($event): void
    {
        $event_create = Event::create([
            'name'        => $event->name,
            'sponsor'     => "afisha7.ru",
            'description' => $event->description,
            'date_start'  => $event->date_start,
            'date_end'    => $event->date_end,
            'user_id'     => 1,
        ]);
        print_r($event_create);
    }
}
