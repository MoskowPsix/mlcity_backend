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
    public function test_get_user_point(): void
    {
        $user = $this->userCreate();
        $points = Point::factory()->count(6)->create([
            'user_id' => $user->id,
        ])->toArray();
        $point_res = [];
        foreach($points as $point) {
            $point_res[] = [
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude']
            ];
        }
        $response = $this->actingAs($user)->getJson(route('point.user.get'));
        $response->assertJson([
            'status' => 'success',
            'points'   => [
                'data' => $point_res
            ]
        ]);
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
