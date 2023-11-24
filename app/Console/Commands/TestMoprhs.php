<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\HistoryContent;
use App\Models\HistoryPrice;
use App\Models\Sight;
use App\Models\HistoryPlace;
use App\Models\HistorySeance;
use Illuminate\Console\Command;

class TestMoprhs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morph';

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
        // $this->testHistoryContents();
        // $historyContents = HistoryContent::find(1);
        
        // $historyContents->historyPlaces()->create([
        //     "address" => "new addres"
        // ]);
        // $historyPlace = HistoryPlace::findOrFail(1)->historySeances()->create(['date_start' => '2022-12-23']);
        // $historySeances = $historyPlace->historySeances->toArray();

        $historyContents = HistoryContent::find(1);

        $historySeance = HistorySeance::find(1);
        $historySeance->update([
            "date_start" => "2022-12-31",
            "date_end"
        ]);
        print_r($historySeance->historyPlace->historyContent->historyContentable->ToArray());
        // $historyContents->historyPrices()->create([
        //     "cost_rub" => "2455"
        // ]);
        // print_r($historyContents->historyPrices->toArray());

        // $historyPrice = HistoryPrice::find(1);
        // print_r($historyPrice->historyContent->historyContentable->price->toArray());

        
        
        
    }

    public function testHistoryContents(){
        $event = Event::find(1);
        $sight = Sight::find(1);


        $event->historyContents()->create([
            "name" => "new names",
            "user_id" => $event->user_id
        ]);
        $event->historyContents()->create([
            "name" => "new names2",
            "user_id" => $event->user_id
        ]);
        $event->historyContents()->create([
            "name" => "new names3",
            "user_id" => $event->user_id
        ]);

        $sight->historyContents()->create([
            "name" => "sight test",
            "user_id" => $sight->user_id
        ]);

        
        $sight->historyContents()->create([
            "name" => "sight test2",
            "user_id" => $sight->user_id
        ]);

        
        $sight->historyContents()->create([
            "name" => "sight test3",
            "user_id" => $sight->user_id
        ]);

        $event = Event::find(1);
        $sight = Sight::find(1);
        
        print_r($event->historyContents->toArray());
        print_r($sight->historyContents->toArray());
    }
}
