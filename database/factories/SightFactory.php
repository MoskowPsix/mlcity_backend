<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sight>
 */
class SightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'              => faker()->sentence(),
            'sponsor'           => faker()->name(),
            'latitude'          => faker()->latitude(),
            'longitude'         => faker()->longitude(),
            'location_id'       => Location::inRandomOrder()->first()->id,
            'address'           => faker()->addres(),
            'description'       => faker()->text(),
            'materials'         => faker()->text(),
            'user_id'           => faker()->User::inRandomOrder()->first()->id,
            'work_time'         => faker()->text(),
            'organization_id'   => Organization::inRandomOrder()->first()->id,
        ];
    }
}
