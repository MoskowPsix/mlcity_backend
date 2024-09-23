<?php

namespace Tests\Feature\Point;

use App\Models\Point;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePointUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_poit_for_user(): void
    {
        $user = $this->userCreate();
        $point = Point::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->deleteJson(route('point.user.delete', ['id' => $point->id]));
        if(!Point::where('id', $point->id)->exists()){
            $response->assertStatus(200);
        } else {
            $this->fail();
        }
        $response->assertStatus(200);
    }
    public function test_delete_point_not_author(): void
    {
        $user1 = $this->userCreate();
        $user2 = $this->userCreate();
        $point = Point::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->deleteJson(route('point.user.delete', ['id' => $point->id]));
        $response->assertStatus(400);
    }
    public function test_delete_point_not_found(): void
    {
        $user = $this->userCreate();
        $response = $this->actingAs($user)->deleteJson(route('point.user.delete', ['id' => 999]));
        $response->assertStatus(400);
    }


    private function userCreate()
    {
        return User::factory()->create();
    }
}
