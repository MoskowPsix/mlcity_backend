<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $user = User::first();
        $organization = Organization::create([
            "name" => "Alex Studio",
            "description" => "test",
            "user_id" => $user->id
        ]);
        dd($organization);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_event_without_permission()
    {
        $user = User::factory()->create();
        $response = $this->get('/');
        dd($user);
        $response->assertStatus(200);
    }
}
