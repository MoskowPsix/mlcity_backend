<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
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
            'name'              => fake()->sentence(),
            'sponsor'           => fake()->name(),
            'description'       => fake()->text(),
            'materials'         => fake()->text(),
            'date_start'        => fake()->dateTimeBetween(startDate: '-7 days', endDate: '+7 days')->format('Y-m-d H:i:s'),
            'date_end'          => fake()->dateTimeBetween(startDate: '+8 days', endDate: '+15 days')->format('Y-m-d H:i:s'),
            'user_id'           => User::inRandomOrder()->first()->id,
            'vk_post_id'        => fake()->numberBetween(100000, 999999),
            'age_limit'         => fake()->numberBetween(0, 24),
        ];
    }
}
