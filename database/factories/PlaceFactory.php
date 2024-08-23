<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Location;
use App\Models\Sight;
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
            'event_id'      => Event::inRandomOrder()->first()->id,
            'sight_id'      => Sight::inRandomOrder()->first()->id,
            'location_id'   => Location::inRandomOrder()->first()->id,
            'latitude'      => $this->faker()->latitude(),
            'longitude'     => $this->faker()->longitude(),
            'address'       => $this->faker()->$this->faker->address(),
        ];
    }
}
