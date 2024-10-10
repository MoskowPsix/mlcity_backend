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
        Sight::all()->each(function($sight) {
            $types = $sight->types()->get();
            foreach ($types as $type) {
                $sight->types()->detach([$type->id]);
                $sight->types()->attach([$type->id]);

            }
        });
        return Command::SUCCESS;
    }
}
