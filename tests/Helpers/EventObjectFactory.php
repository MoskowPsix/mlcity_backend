<?php

namespace Tests\Helpers;

use App\Models\Event;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\User;

class EventObjectFactory {

    public static function createEvent(): array {
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


        foreach ($places as &$place) {
            $seance = Seance::factory()->count(3)->make([
                "dateStart" => fake()->date(),
                "dateEnd" => fake()->date()
            ])->toArray();

            $place['seances'] = $seance;
        }

        $types = "2, 3";

        $data = [
            ...$event->toArray(),
            "prices" => $prices->toArray(),
            "places" => $places,
            "type"  => [$types]
        ];

        return $data;
    }
}
