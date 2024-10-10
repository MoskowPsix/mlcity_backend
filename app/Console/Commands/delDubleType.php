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
    protected $signature = 'duble';

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
        Sight::query()->orderBy('id')->chunk(1000, function ($sight) {
            $sight->each(function($sight) {
                $types = $sight->types->pluck('id')->toArray();
                $sight->types()->detach($types);
                $sight->types()->attach(array_unique($types));
            });
        });
        return Command::SUCCESS;
    }
}
