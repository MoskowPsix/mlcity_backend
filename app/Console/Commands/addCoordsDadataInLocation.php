<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;

class addCoordsDadataInLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-coords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for only one download locations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dadata = new \Dadata\DadataClient(env('DADATA_APP_KEY'), env('DADATA_SECRET_KEY'));
        $locations = Location::query()->get();
        foreach($locations as $location) {
            if (!isset($location->info_dadata)) {
                $location = Location::with('locationParent')->findOrFail($location->id);
                if (isset($location->locationParent)) {
                    $response = $dadata->clean("address", $location->locationParent['name'] .' ' . $location->name);

                    $location->update([
                        'latitude' => $response['geo_lat'],
                        'longitude' => $response['geo_lon'],
                        'info_dadata' => json_encode($response),
                        'time_zone_utc' => $response['timezone'],
                    ]);
                } else {
                    $response = $dadata->clean("address", $location->name);

                    $location->update([
                        'latitude' => $response['geo_lat'],
                        'longitude' => $response['geo_lon'],
                        'info_dadata' => json_encode($response),
                        'time_zone_utc' => $response['timezone'],
                    ]);

                }
            }
        }
        return 0;
    }
}
