<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seance>
 */
class SeanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "date_start"    => fake()->dateTimeBetween(startDate: '-7 days', endDate: '+7 days')->format('Y-m-d H:i:s'),
            "date_end"      => fake()->dateTimeBetween(startDate: '+8 days', endDate: '+15 days')->format('Y-m-d H:i:s'),
        ];
    }
}
