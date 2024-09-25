<?php

namespace App\Console\Commands;

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
        $currentType = new \App\Contracts\Services\CurrentType\CurrentType('Библиотека');
        print_r($currentType);
        return Command::SUCCESS;
    }
}
