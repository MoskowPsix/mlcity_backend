<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
use Database\Seeders\test\TestSightsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class HistoryContentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_history_content_for_sight_with_standart_attrs()
    {
        $this->seed();
        $this->seed([
            TestSightsSeeder::class
        ]);
        $user = User::find(1);
        $sight = Sight::find(1);

        $data = [
            "id" => $sight->id,
            "type" => "Sight",

            "history_content" => [
                "name" => "new name",
                "sponsor" => "new sponsor",
                "address" => "new address",
                "latitude" => 50,
                "longitude" => 51,
                "description" => "new description",
            ]
        ];

        $response = $this->actingAs($user)
        ->postJson("/api/history-content", $data);

        $response->assertStatus(201);
        $response->assertSee($data['history_content']);


    }


}
