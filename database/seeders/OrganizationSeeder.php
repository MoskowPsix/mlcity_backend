<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Organization;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sight = Sight::first();
        $org = Organization::create([
            "name" => "Начальное сообщество",
            "sight_id" => $sight->id
        ]);
        $location = Location::where("name", "Москва")->get()->first();
        $org->locations()->attach($location->id);
    }
}
