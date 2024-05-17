<?php

namespace Database\Factories;

use App\Models\Sight;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
           "sight_id" => function () {
            return Sight::factory()->create()->id;
           },
           "cost_rub" => $this->faker->numberBetween(0,10000),
           "descriptions" => $this->faker->text()
        ];
    }
}