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
use Tests\Helpers\EventObjectFactory;
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

        $data = EventObjectFactory::createEvent();

        $response = $this->actingAs($user)->post('api/events/create', $data);

        $response->assertStatus(200);

    }

    public function testCreateEventWithoutUser()
    {
        $data = EventObjectFactory::createEvent();

        $response = $this->post('api/events/create', $data);

        $response->assertStatus(403);
    }
}
