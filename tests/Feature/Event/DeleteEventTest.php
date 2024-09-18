<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\EventObjectFactory;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('event.delete', ['id' => $event->id]));
        if(!Event::where('id', $event->id)->exists()){
            $response->assertStatus(200);
        } else {
            $this->fail();
        }
    }
    public function test_delete_event_not_found_event()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('event.delete', ['id' => 555]));

        $response->assertStatus(400);
    }
    public function test_delete_event_not_author_event()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->deleteJson(route('event.delete', ['id' => $event->id]));
        $response->assertStatus(400);
    }
}
