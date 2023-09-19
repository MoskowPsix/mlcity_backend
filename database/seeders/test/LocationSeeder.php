<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location0 = new Location();
        $location0 ->name = 'РФ';
        $location0->time_zone = 'Europe/Moscow';
        $location0->cult_id = 1;
        $location0->save();

        $location1 = new Location();
        $location1->name = 'Тестовая область';
        $location1->time_zone = 'Asia/Yakutsk';
        $location1->location_id = 1;
        $location1->cult_id = 209;
        $location1->save();

        $location2 = new Location();
        $location2->name = 'Московская область';
        $location2->time_zone = 'Europe/Moscow';
        $location2->location_id = 1;
        $location2->cult_id = 7;
        $location2->save();

        $location3 = new Location();
        $location3->name = 'Алтайская край';
        $location3->time_zone = 'Asia/Krasnoyarsk';
        $location3->location_id = 2;
        $location3->cult_id = 209;
        $location3->save();

        $location4 = new Location();
        $location4->name = 'Астраханская область';
        $location4->time_zone = 'Europe/Samara';
        $location4->location_id = 1;
        $location4->cult_id = 170;
        $location4->save();

        $location5 = new Location();
        $location5->name = 'Архангельская область';
        $location5->time_zone = 'Europe/Moscow';
        $location5->location_id = 1;
        $location5->cult_id = 169;
        $location5->save();
    }
}
