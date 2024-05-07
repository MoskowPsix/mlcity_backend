<?php

namespace App\Console\Commands;

use App\Models\EventType;
use Illuminate\Console\Command;
use App\Models\Location;

class DisplayUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'display:upd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the display field for the locations table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $state = true;

        if (($handle = fopen("./database/seeders/csvs/display_upd.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Первыая строка содержит название полей, пропускаем её
                if ($state) {
                    $state = false;
                } else {
                    // Бывают некоректные строки, избавляемся от них проверяя наличие id у записи
                    if($data[0] < 10000 && 0 < $data[0]) {
                        $location = Location::findOrFail($data[0]);
                        $location->update([
                                "display" => $data[2]
                            ]);
                    }
                }
            }
            fclose($handle);
        }
        return 0;

    }
}
