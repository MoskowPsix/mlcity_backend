<?php

namespace App\Console\Commands;

use App\Models\EventType;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test_e';

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
        $allStypes = json_decode(file_get_contents('https://culture.ru/api/rubrics?sort=level', true));

        foreach($allStypes->items as $type){
            print("--------"."\n");
            print($type->_id . "\n");
            print($type->title . "\n");
            print_r($type->name . "\n");
            print("++++++++"."\n");
            print("\n");
        }

    }
}
