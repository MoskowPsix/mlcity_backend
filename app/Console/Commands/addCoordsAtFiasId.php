<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Exception;


class addCoordsAtFiasId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coords-fias';

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
        $locations = Location::query()->get();
        foreach($locations as $location) {
            if ((!isset($location->latitude) || !isset($location->longitude)) && isset($location->info_dadata)) {
                $location = Location::with('locationParent')->findOrFail($location->id);
                if (!(!isset($location->locationParent) || ($location->locationParent->id == 1))) {
                    print($location->locationParent->id. ' | ');
                    if ($location->locationParent['name'] == 'Донецкая Народная Республика' || $location->locationParent['name'] == 'Луганская Народная Республика' ) {
                        $address = 'Украина ' . $location->name;    // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                    } else {
                        $address = $location->locationParent['name'] .' ' . $location->name;
                        $location->id == 12 ? $address = 'Украина Дрнецкая область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                        $location->id == 33 ? $address = 'Украина Луганская область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                        $location->id == 33 ? $address = 'Украина Херсонская область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                    }
                } else {
                    $address = $location->name;
                    $location->id == 12 ? $address = 'Украина Дрнецкая область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                    $location->id == 33 ? $address = 'Украина Луганская область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                    $location->id == 33 ? $address = 'Украина Херсонская область' : null; // Яндекс спустя почти два года не признал ДНР и ЛНР (иначе не достать координаты)
                }
                print($location->id);
                $response = $this->getCoordsByAddress($address);
                $location->update([
                    'latitude' => $response[0],
                    'longitude' => $response[1],
                ]);
            }
        }
        return 0;
    }
    public function getCoordsByAddress($address) {
        $format = 'json';
        $key = 'cc16faa8-6cc7-4e6d-b21e-76a0a39f7fe5';
        try {
            print_r($address);
            $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$key.'&format='.$format.'&geocode=' . urlencode($address));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $res = curl_exec($ch);
            curl_close($ch);
            
            $res = json_decode($res, true);
            $coords = explode(' ',$res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
            print_r($coords);
            return $coords;
        }  catch (Exception $e) {
            Log::error('Ошибка при получении адреса: '.json_decode($e));
            sleep(2);
            $this->getCoordsByAddress($address);
        }         
    }
}
