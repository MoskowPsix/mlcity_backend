<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Organization;
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
            'name' => $this->faker()->sentence(),
            'sponsor' => $this->faker()->name(),
            'latitude' => $this->faker()->latitude(),
            'longitude' => $this->faker()->longitude(),
            'location_id' => Location::inRandomOrder()->first()->id,
            'address' => $this->faker()->addres(),
            'description' => $this->faker()->text(),
            'materials' => $this->faker()->text(),
            'user_id' => $this->faker()->User::inRandomOrder()->first()->id,
            'work_time' => $this->faker()->text(),
            'organization_id' => Organization::inRandomOrder()->first()->id,
        ];
    }
}
