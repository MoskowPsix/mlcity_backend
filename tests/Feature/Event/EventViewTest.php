<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventViewTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;
    /**
     * A basic feature test example.
     */
    public function test_add_view(): void
    {
        $user = $this->createUser();
        $event = $this->createEvent();

        $response = $this->actingAs($user)->getJson(route('event.view', ['id' => $event->id]));

        $response->assertStatus(200);
    }
    // public function test_add_many_view(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    // public function test_add_retry_user_view(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    // public function test_event_not_found(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    private function createUser(): User
    {
        return User::factory()->create();
    }
    private function createUsers(): User
    {
        return User::factory()->count(5)->create();
    }
    private function createEvent(): Event
    {
        return Event::factory()->create();
    }
}
