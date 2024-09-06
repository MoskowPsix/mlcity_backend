<?php

namespace Tests\Feature\Sight;

use App\Models\SightType;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\SightObjectFactory;
use Tests\TestCase;

class CreateSightTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_create_sight()
    {
        $user = User::factory()->create();
        $status = Status::where("name", "Опубликовано")->get()->first();
        $coords = fake()->latitude() . "," . fake()->longitude();
        $sightTypes = SightType::first();
        $types = $sightTypes->id . ",";

        $data = SightObjectFactory::createFullSightObjectForRequest();
        $data["status"] = $status->id;
        $data['coords'] = $coords;
        $data['type']   = $types;

        $response = $this->actingAs($user)->postJson("api/sights/create", $data);

        $response->assertStatus(201);
    }
}
