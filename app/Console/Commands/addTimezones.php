<?php

namespace App\Console\Commands;

use App\Models\Timezone;
use Illuminate\Console\Command;

class addTimezones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add_timezones';

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
            Timezone::create($timezone);
        }
    }
}
