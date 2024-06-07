<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timezones = [
            ['name' => "Europe/Kaliningrad", 'UTC' => "UTC +2"],
            ['name' => "Europe/Moscow", 'UTC' => "UTC +3"],
            ['name' => "Europe/Samara", 'UTC' => "UTC +4"],
            ['name' => "Asia/Yekaterinburg", 'UTC' => "UTC +5"],
            ['name' => "Asia/Omsk", 'UTC' => "UTC +6"],
            ['name' => "Asia/Krasnoyarsk", 'UTC' => "UTC +7"],
            ['name' => "Asia/Irkutsk", 'UTC' => "UTC +8"],
            ['name' => "Asia/Yakutsk", 'UTC' => "UTC +9"],
            ['name' => "Asia/Vladivostok", 'UTC' => "UTC +10"],
            ['name' => "Asia/Magadan", 'UTC' => "UTC +11"],
            ['name' => "Asia/Kamchatka", 'UTC' => "UTC +12"]
        ];

        foreach($timezones as $timezone){
            if(!Timezone::where('name', $timezone['name'])->first()) {
                Timezone::create($timezone);
            }
        }
    }
}
