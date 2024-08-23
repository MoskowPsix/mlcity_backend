<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventPriceFactory extends Factory
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
            "cost_rub"      => $this->faker->numberBetween(0,10000),
            "descriptions"  => $this->faker->text()
        ];
    }
}
