<?php

namespace Tests\Feature\Event;

use App\Models\Sight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\EventObjectFactory;
use Tests\TestCase;

class CreateEventTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    public function testCreateFirstUserEvent()
    {
        $user = User::factory()->create();

        $data = EventObjectFactory::createFullEventObjectForRequest();


        $response = $this->actingAs($user)->postJson('api/events/create', $data);

        $response->assertStatus(201);
        // $this->assertDatabaseHas("organizations", [
        //     "sight_id" => $user->id,
        //     "name" => $user->name
        // ]);

    }
    public function testCreateEvent()
    {
        $user = User::first();

        $data = EventObjectFactory::createFullEventObjectForRequest();

        $response = $this->actingAs($user)->postJson('api/events/create', $data);

        $response->assertStatus(201);
    }

    public function testCreateEventWithNotUserOrganization()
    {
        $user = User::first();

        $sight = Sight::factory()->create([
            "user_id" => $user->id,
        ]);

        $organization = $sight->organization()->create();

        $user2 = User::factory()->create();

        $data = EventObjectFactory::createFullEventObjectForRequest();
        $data["organization_id"] = $organization->id;

        $response = $this->actingAs($user2)->postJson('api/events/create', $data);

        $response->assertStatus(403);
    }

    public function testCreateEventWithoutUser()
    {
        $data = EventObjectFactory::createFullEventObjectForRequest();

        $response = $this->postJson('api/events/create', $data);

        $response->assertStatus(401);
    }
}
