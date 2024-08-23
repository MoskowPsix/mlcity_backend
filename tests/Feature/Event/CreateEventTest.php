<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateEventTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateEvent()
    {
        $user = User::first();
        $event = Event::factory()
        ->make([
            "dateStart" => fake()->date(),
            "dateEnd" => fake()->date()
        ]);

        $location = Location::inRandomOrder()->first();
        $sight = Sight::factory()->create();

        $prices = Price::factory()->count(3)->make();
        $places = Place::factory()->count(2)->make([
            "coords" => fake()->longitude() . "," . fake()->latitude(),
            "locationId" => $location->id,
            "sightId" => $sight->id
        ])->toArray();


        foreach ($places as $place) {
            $seance = Seance::factory()->count(3)->make()->toArray();

            $place['seances'] = $seance;
            dd($place);
        }

        $data = [
            ...$event->toArray(),
            "prices" => $prices->toArray(),
            "places" => $places
        ];

        dd($data);

        $response = $this->actingAs($user)->post('api/events/create', $data);

        $response->assertStatus(200);

    }
}
