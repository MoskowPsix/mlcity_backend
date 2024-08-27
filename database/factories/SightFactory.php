<?php

namespace Database\Factories;

use App\Models\Location;
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
            'name'              => fake()->sentence(),
            'sponsor'           => fake()->name(),
            'latitude'          => fake()->latitude(),
            'longitude'         => fake()->longitude(),
            'location_id'       => Location::inRandomOrder()->first()->id,
            'address'           => fake()->address(),
            'description'       => fake()->text(),
            'materials'         => fake()->text(),
            'user_id'           => User::inRandomOrder()->first()->id,
            'work_time'         => fake()->text(),
        ];
    }
}
