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
            'latitude'          => fake()->latitude(),
            'longitude'         => fake()->longitude(),
            'location_id'       => Location::inRandomOrder()->first()->id,
            'address'           => fake()->address(),
            'description'       => fake()->text(),
            'materials'         => fake()->text(),
            'user_id'           => User::first()->id,
            'work_time'         => fake()->text(),
            'phone_number'      => (string)fake()->numberBetween(9999999999, 1000000000),
            'email'             => fake()->email(),
        ];
    }
}
