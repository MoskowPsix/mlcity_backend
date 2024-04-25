<?php

namespace Database\Factories;

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
            "name" => $this->faker->sentence(),
            "sponsor" => $this->faker->name(),
            "address" => $this->faker->address(),
            "latitude" => $this->faker->latitude(),
            "longitude" => $this->faker->longitude(),
            "description" => $this->faker->text(),
            "user_id" => User::first()->id,
            "work_time" => $this->faker->text()
        ];
    }
}
