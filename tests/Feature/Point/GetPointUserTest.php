<?php

namespace Tests\Feature\Point;

use App\Http\Resources\Point\GetPoint\SuccessGetPointsResource;
use App\Models\Point;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPointUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_user_point()
    {
        $user = $this->userCreate();
        $points = Point::factory()->count(6)->create([
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user)->getJson(route('point.user.get'));
        $response->assertJson($this->getResource(new SuccessGetPointsResource($points->cursorPaginate())));
    }

    private function userCreate()
    {
        return User::factory()->create();
    }

    private function getResource($resource): array
    {
        return json_decode($resource->toJson(), true);
    }
}
