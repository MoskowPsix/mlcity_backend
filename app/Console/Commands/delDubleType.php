<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Place;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Console\Command;

class delDubleType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'double-type:del';

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
        $bar = $this->output->createProgressBar(Sight::query()->count());
        Sight::query()->orderBy('id')->chunk(1000, function ($sight) use($bar) {
            $sight->each(function($sight) use($bar) {
                if (isset($sight->organization_id) && Event::where('organization_id', $sight->organization->id)->exists()){
                    $types = $sight->types->pluck('id')->toArray();
                    $sight->types()->detach($types);
                    $sight->types()->attach(array_unique($types));
                    $bar->advance();
                } else {
                    $status = Status::where('name', 'Отказ')->first();
                    $statuses = $sight->statuses;
                    foreach($statuses as $status) {
                        $sight->statuses()->updateExistingPivot($status["id"], [
                            "last" => false
                        ]);
                    }
                    $sight->statuses()->attach($status->id, ["last" => True]);
                    $bar->advance();
                }
            });
        });
        $bar->finish();

        return Command::SUCCESS;
    }
}
