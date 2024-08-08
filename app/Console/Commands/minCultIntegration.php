<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventType;
use App\Models\FileType;
use App\Models\Location;
use App\Models\Place;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Timezone;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Exception;


# 21.35
#
class minCultIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:min-cult {type?} {offset?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private array $error_types = [];

    private int $limit = 10;

    private int $offset = 1;

    private int $numberOfProcess = 10;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->argument('type') == 'all') {
            if ($this->argument('offset')) {
//                print('offset');
                $this->setEvents();
            } else {
//                print('not offset');
                $this->startInt();
            }
        } else {
            print('argument not found');
        }
        return Command::SUCCESS;
    }

    private function startInt(): void
    {
        $progress = 0;
        $total =  json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"2024-06-06"}}&l=1', true, $this->getHeader()))->total;
        while($total >= 1) {
            $start_timer = microtime(true);
            $this->startCommands();
            $total = $total - $this->limit * $this->numberOfProcess;
            $progress = $progress + $this->limit * $this->numberOfProcess;
            $end_time = ((microtime(true) - $start_timer) / 60)  * ($total / 10);
            info($progress . ' | ' . $total . ' | ' . (int)$end_time . 'min' . "\n");
        }
    }
    private function startCommands(): void
    {
        for ($i = 0; $i < $this->numberOfProcess; $i++) { // Запускаем команды по загрузке sight ['php', 'artisan', 'institutes_save', $page, $limit]
            $process = new Process(['php', 'artisan', 'integration:min-cult', $this->argument('type'), $this->offset]);
            $process->setTimeout(0);
            $process->disableOutput();
            $process->start();
            $processes[] = $process;
            $this->offset = $this->offset + $this->limit;
        }
        while (count($processes)) {
            foreach ($processes as $i => $runningProcess) {
                // этот процесс завершен, поэтому удаляем его
                if (!$runningProcess->isRunning()) {
                    unset($processes[$i]);
                }
            }
        }
    }
    public function setEvents(): void
    {
        $offset = $this->argument('offset');
        $response = json_decode(file_get_contents('https://opendata.mkrf.ru/v2/events/$?f={"data.general.end":{"$gt":"2024-06-06"}}&l=' . $this->limit . '&s=' . $offset, true, $this->getHeader()));
        $events = $response->data;
        foreach($events as $event) {
            $this->saveEvent($event);
        }
    }

    private function getHeader() {
        // Create a stream
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-API-KEY: ".env('API_KEY_MIN_CULT')."\r\n"
            ]
        ];
        return stream_context_create($opts);
    }

    private function saveEvent($event): void
    {
        if (!Event::where('min_cult_id', $event->data->general->id)->exists()){
            $event_cr = Event::create([
                'name' => $event->nativeName,
                'sponsor' => $event->data->general->organization->name,
                'description' => $event->data->general->description,
                'materials' => isset($event->data->general->saleLink) ? $event->data->general->saleLink : null,
                'date_start' => $event->data->general->start,
                'date_end' => $event->data->general->end,
                'user_id' => 1,
                'min_cult_id' => $event->data->general->id,
            ]);
            $event = $event->data->general;
            $this->saveType($event->category, $event_cr);

            isset($event->price) ? $this->savePrice($event->price, $event_cr) : null;
            isset($event->maxPrice) ? $this->savePrice($event->maxPrice, $event_cr) : null;

            isset($event->image) ? $this->saveFiles($event->image, $event_cr) : null;
            if(isset($event->gallery)){
                foreach($event->gallery as $file) {
                    $this->saveFiles($file, $event_cr);
                }
            }

            foreach($event->places as $place) {
                $this->savePlace($event->seances, $place, $event_cr);
            }
            $this->setStatus($event_cr);
        }
    }

    private function savePlace(array $seances, object $place, Event $event): void
    {
            $location = $this->searchLocation($place->address->mapPosition->coordinates);
            $sight = $this->searchSight($place->address->mapPosition->coordinates, $place->address->fullAddress);
            $timezone_id = Timezone::where('UTC', $location->time_zone_utc)->first()->id;
            $place_cr =  $event->places()->create([
                'address' => $place->address->fullAddress,
                'location_id' => $location->id,
                'latitude' => $place->address->mapPosition->coordinates[0],
                'longitude' => $place->address->mapPosition->coordinates[1],
                'timezone_id' => $timezone_id,
            ]);
            !empty($sight) ? $place_cr->sight()->save($sight) : null;
            foreach($seances as $seance){
                $this->saveSeance($seance, $place_cr);
            }
    }
    private function saveSeance(Object $seance, Place $place): void
    {
        $seance = $place->seances()->create([
            'date_start' => $seance->start,
            'date_end' => $seance->end,
        ]);
    }
    private function saveType(Object $type, Event $event): void
    {
        $type_search = EventType::where('name', $type->name)->first();
        if (isset($type_search)) {
            $event->types()->attach($type_search->id);
        } else {
            !array_search($type->name, $this->error_types) ? $this->error_types[] = $type->name : null;
            !array_search($type->name, $this->error_types) ? print($type->name) : null;
        }
    }
    private function setStatus(Event $event):void
    {
        $status = Status::where('name', 'Опубликовано')->first();
        $event->statuses()->updateExistingPivot( $status, ['last' => false]);
        $event->statuses()->attach($status->id,  ['last' => true]);
    }
    private function savePrice(int $price, Event $event): void
    {
        $event->price()->create([
            'cost_rub' => $price
        ]);
    }
    private function saveFiles(Object $file, Event $event):void
    {
        $file_type = FileType::where('name', 'image')->first();
        $event->files()->create([
            'name' => uniqid('img_'),
            'link' => $file->url,
            'local'=> 0,
        ])->file_types()->attach($file_type->id);
    }
    private function searchLocation(array $coords): Location
    {
        $latitude = $coords[0];
        $longitude = $coords[1];
        $radius = 5;
        $searchForCoord = '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude ))) ) <= ? ';

        while(empty($location) == true){
            $location = Location::with('locationParent')->whereRaw($searchForCoord,  [$latitude, $longitude,  $latitude,  $radius])->first();
            $radius = $radius + 5;
        }
        return $location;
    }
    private function searchSight(array $coords, string $address): Sight | null
    {
        $sight_address = Sight::where('address','ILIKE', '%'.$address.'%')->first();
        $sight_coords = Sight::where('latitude', $coords[0])->where('longitude', $coords[1])->first();

        isset($sight_address) ? $sight = $sight_address : $sight = null;
        isset($sight_coords) && !isset($sight_address) ? $sight = $sight_coords : null;

        return $sight;
    }
}
