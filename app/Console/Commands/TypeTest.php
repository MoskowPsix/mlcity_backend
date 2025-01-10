<?php

namespace App\Console\Commands;

use App\Events\Notify\StartHoursEventNotify;
use App\Events\TestEvent;
use Elastic\Elasticsearch\Client;
use App\Models\Event;
use App\Models\Notify;
use App\Models\Place;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;

class TypeTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'type';

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
        $chunk_size = 1000;
        $bar_event = $this->output->createProgressBar(Place::query()->count());
        Place::chunk($chunk_size, function ($places) use($bar_event) {
            $places->each(function($place) use($bar_event) {
                try {
                    if (resolve(Client::class)->indices()->exists(['index' => $place->getSearchIndex()])->getStatusCode() == 404) {
                        $params = [
                            'index' => $place->getSearchIndex(),
                            'body' => [
                                'mappings' => [
                                    'properties' => [
                                        'location' => [
                                            'type' => 'geo_point'
                                        ],
                                        'seances' => [
                                            "type" => "nested",
                                            'properties' => [
                                                'date_start' => [
                                                    'type' => 'date'
                                                ],
                                                'date_end' => [
                                                    'type' => 'date'
                                                ],
                                            ],
                                        ],
                                        'status' => [
                                            'type' => 'nested',
                                            'properties' => [
                                                'name' => [
                                                    'type' => 'text'
                                                ],
                                                'last' => [
                                                    'type' => 'boolean'
                                                ],
                                            ]
                                        ],
                                    ]
                                ]
                            ]
                        ];
                        resolve(Client::class)->indices()->create($params);
                    }
                    resolve(Client::class)
                        ->setElasticMetaHeader(true)
                        ->index([
                            'index' => $place->getSearchIndex(),
                            'type' => $place->getSearchType(),
                            'id' => $place->getKey(),
                            'body' => $place->toSearchArray(),
                        ]);
                    $bar_event->advance();
                } catch (Exception $e) {
//                    dd($e);
                }
            });
        });
        return Command::SUCCESS;
    }
}
