<?php

namespace Database\Factories;

use App\Models\Sight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SightPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sight_id'      => Sight::inRandomOrder()->first()->id,
            "cost_rub"      => $this->faker->numberBetween(0,10000),
            "descriptions"  => $this->faker->text()
        ];
    }
}
