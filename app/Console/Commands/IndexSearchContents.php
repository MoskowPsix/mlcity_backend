<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Sight;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class indexSearchContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexing contents for elasticsearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('elasticsearch.enabled'))
        {
            $this->warn('Elasticsearch is not enabled');
            return 0;
        }
        $chunk_size = 100;
        ini_set('memory_limit', '2048M');
        $bar_event = $this->output->createProgressBar(Event::query()->count());
        Event::with('files')->chunk($chunk_size, function ($event) use($bar_event) {
            $event->each(function($event) use($bar_event) {
                resolve(Client::class)
                    ->setElasticMetaHeader(true)
                    ->index([
                    'index' => $event->getSearchIndex(),
                    'type' => $event->getSearchType(),
                    'id' => $event->getKey(),
                    'body' => $event->toSearchArray(),
                ]);
                $bar_event->advance();
            });
        });
        $bar_sight = $this->output->createProgressBar(Sight::query()->count());
        Sight::with('files')->chunk($chunk_size, function ($sight) use($bar_sight) {
            $sight->each(function($sight) use($bar_sight) {
                resolve(Client::class)
                    ->setElasticMetaHeader(true)
                    ->index([
                        'index' => $sight->getSearchIndex(),
                        'type' => $sight->getSearchType(),
                        'id' => $sight->getKey(),
                        'body' => $sight->toSearchArray(),
                    ]);
                $bar_sight->advance();
            });
        });
        return Command::SUCCESS;
    }
}
