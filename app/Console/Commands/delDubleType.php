<?php

namespace App\Console\Commands;

use App\Models\Sight;
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
        ini_set('memory_limit', '2048M');
        $bar = $this->output->createProgressBar(Sight::query()->count());
        Sight::query()->orderBy('id')->chunk(1000, function ($sight) use($bar) {
            $sight->each(function($sight) use($bar) {
                $types = $sight->types->pluck('id')->toArray();
                $sight->types()->detach($types);
                $sight->types()->attach(array_unique($types));
                $bar->advance();
            });
        });
        $bar->finish();

        return Command::SUCCESS;
    }
}
