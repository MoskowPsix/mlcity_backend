<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class minCultIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:min-cult';

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
        try {
            $cursor = ' ';
            while(!empty($cursor)) {
                $response = json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"2024-06-06"}}&l=10&cursor=' . $cursor, true, $this->getHeader()));
                $events = $response->data;
                $response->cursor ? $cursor = $response->cursor : $cursor = null;
                foreach($events as $event) {
                    $this->saveEvent($event);
                }
            }
        }  catch (Exception $e) {
            Log::error('Ошибка при получении страницы events');
        }
        // $events = json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"2024-06-06"}}&o=data.general.start&l=10', true));

        return Command::SUCCESS;
    }

    public function getHeader() {
        // Create a stream
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-API-KEY: 37d90b285675baf10aab99564b105bb255afcac60ccb70e919ebb554666974e6\r\n"
            ]
        ];
        return stream_context_create($opts);
    }

    public function saveEvent($event) {
        try {
            $event_cr = [
                'name' => $event->nativeName,
                'sponsor' => $event->data->general->organization->name,
                'description' => $event->data->general->description,
                'materials' => isset($event->data->general->saleLink) ? $event->data->general->saleLink : null,
                'date_start' => $event->data->general->start,
                'date_end' => $event->data->general->end,
                'user_id' => 1,
                'min_cult_id' => $event->data->general->id,
            ];
            $places = $event->data->general->places;
            foreach($places as $place) {
                $this->savePlace($place,);
            }
        }  catch (Exception $e) {
            Log::error('Ошибка при устанвке events');
        }
    }

    public function savePlace($place, $event_id) {
        $place_cr =  [
            'event_id' => $event_id,
            'address' => $place->address->fullAddress,
            'location_id' => $place->address->mapPosition->coordinates[0],
            'latitude' => $place->address->mapPosition->coordinates[0],
            'longitude' => $place->address->mapPosition->coordinates[1],
            // 'sight_id' => ,
            // 'timezone_id' => ,
        ];
    }
}
