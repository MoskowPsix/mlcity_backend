<?php

namespace Tests\Feature\Point;

use App\Models\Point;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorePointUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_point_create()
    {
        $user = $this->userCreate();
        $point_params = Point::factory()->make()->toArray();
        $response = $this->actingAs($user)->postJson(route('point.user.store'), $point_params);
        $response->assertStatus(201);
    }

    private function userCreate()
    {
        return User::factory()->create();
    }
}
