<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->sentence(),
            "sponsor" => $this->faker->name(),
            "description" => $this->faker->text(),
            "materials" => $this->faker->text(),
            "date_start" => $this->faker->date("yyyy-mm-dd"),
            "date_end" => $this->faker->date("yyyy-mm-dd")
        ];
    }
}
