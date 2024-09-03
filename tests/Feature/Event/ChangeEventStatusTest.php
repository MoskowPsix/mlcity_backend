<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangeEventStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;


    public function test_add_status()
    {
        $user = User::first();

        $event = Event::factory()->create([
            "user_id" => $user->id
        ]);


        $response = $this->actingAs($user)->post('/api/events/' . $event->id . '/statuses', [
            "status_id" => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_add_status_to_event_by_other_user()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create();


        $response = $this->actingAs($user)->post('/api/events/' . $event->id . '/statuses', [
            "status_id" => 2
        ]);

        $response->assertStatus(403);
    }

    public function test_add_nont_existed_status()
    {
        $user = User::first();

        $event = Event::factory()->create([
            "user_id" => $user->id
        ]);


        $response = $this->actingAs($user)->post('/api/events/' . $event->id . '/statuses', [
            "status_id" => 2
        ]);

        $response->assertStatus(200);
    }
}
