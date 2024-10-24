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
        $sight = Sight::factory()->create();
    }
}
