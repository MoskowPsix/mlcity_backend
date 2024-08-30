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

    public static function createFullEventObjectForRequest(): array {
        $date1 = fake()->dateTimeBetween('-1 year', 'now');
        $date2 = fake()->dateTimeBetween($date1->format('Y-m-d H:i:s'), 'now')->format('Y-m-d H:i:s');
        $status = 2;

        $event = Event::factory()
        ->make([
            "dateStart" => $date1->format('Y-m-d H:i:s'),
            "dateEnd" => $date2,
            "status" => $status,
        ]);

        // dd([$date1, $date2]);

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

    public static function createEventInDB(int $count, bool $withPlaces)
    {
        if ($withPlaces)
        {
            Event::factory()->hasPlaces(3)->count($count)->create();
        } else {
            Event::factory()->count($count)->create();

        }
    }
}
