<?php

namespace Tests\Feature\Event;
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


        $response = $this->actingAs($user)->post('api/events/create', $data);

        $response->assertStatus(200);
        // $this->assertDatabaseHas("organizations", [
        //     "sight_id" => $user->id,
        //     "name" => $user->name
        // ]);

    }
    public function testCreateEvent()
    {
        $user = User::first();

        $data = EventObjectFactory::createFullEventObjectForRequest();

        $response = $this->actingAs($user)->post('api/events/create', $data);

        $response->assertStatus(200);

    }

    public function testCreateEventWithoutUser()
    {
        $data = EventObjectFactory::createFullEventObjectForRequest();

        $response = $this->post('api/events/create', $data);

        $response->assertStatus(403);
    }
}
