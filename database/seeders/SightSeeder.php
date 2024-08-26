<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Organization;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location = Location::where("name", "Москва")->get()->first();
        $user = User::first();
        $org = Sight::create([
            "name" => "Начальное сообщество",
            "user_id" => $user->id,
            "address" => fake()->address(),
            "latitude" => $location->latitude,
            "longitude" => $location->longitude,
            "description" => ""
        ]);

    }
}
