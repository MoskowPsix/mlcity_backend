<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "latitude" => $this->faker->latitude(),
            "longitude" => $this->faker->longitude(),
            "location_id" => Location::inRandomOrder()->first()->id,
            "address" => $this->faker->address()
        ];
    }
}
