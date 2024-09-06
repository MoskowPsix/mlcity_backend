<?php

namespace Tests\Helpers;

use App\Models\Event;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Seance;
use App\Models\Sight;
use App\Models\User;

class SightObjectFactory {

    public static function createFullSightObjectForRequest(): array
    {
        $sight = Sight::factory()->make([
            "locationId" => Location::inRandomOrder()->first()->id,
        ]);

        $data = [
            ...$sight->toArray()
        ];

        return $data;
    }
}
