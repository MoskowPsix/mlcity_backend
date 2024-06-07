<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Price;
use App\Models\Sight;
use App\Models\SightType;
use App\Models\User;
use Database\Seeders\test\TestSightsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryContentSightTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_history_content_for_sight_with_standart_attrs()
    {
        $this->prepare_seeds_for_sight_tests();

        $user = User::first();
        $sight = Sight::factory()->create();

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
                "work_time"=> "new work_timessss"
            ]
        ];

        $response = $this->actingAs($user)
        ->postJson("/api/history-content", $data);

        $response->assertStatus(201)
        ->assertJsonFragment($data['history_content']);
    }

    public function test_create_history_content_for_sight_with_new_prices(){
        $this->prepare_seeds_for_sight_tests();

        $user = User::first();
        $sight = Sight::factory()->create();
        $price = Price::factory()->create();
        $price2 = Price::factory()->create();
        $price3 = Price::factory()->create();

        $data = [
            "id" => $sight->id,
            "type" => "Sight",

            "history_content" => [
                "history_prices" => [
                    [
                        "cost_rub" => $price->cost_rub,
                        "descriptions" => $price->descriptions
                    ],
                    [
                        "cost_rub" => $price2->cost_rub,
                        "descriptions" => $price2->descriptions
                    ],
                    [
                        "cost_rub" => $price3->cost_rub,
                        "descriptions" => $price3->descriptions
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($user)
        ->postJson("/api/history-content",$data);

        $response->assertStatus(201);

        foreach($data["history_content"]["history_prices"] as $price){
            $this->assertDatabaseHas("history_prices", [
                "history_content_id" =>$response["history_content"]["id"],
                "cost_rub" => $price["cost_rub"],
                "descriptions" => $price["descriptions"]
            ]);
        }
    }

    public function test_create_history_content_for_sight_with_new_types(){
        $this->prepare_seeds_for_sight_tests();
        $type1 = SightType::inRandomOrder()->first();
        $type2 = SightType::inRandomOrder()->first();
        $type3 = SightType::inRandomOrder()->first();
        $sight = Sight::first();
        $user = User::first();

        $data = [
            "id" => $sight->id,
            "type" => "Sight",
            "history_content" => [
                "history_types" => [
                    [
                        "id" => $type1->id
                    ],
                    [
                        "id" => $type2->id
                    ],
                    [
                        "id" => $type3->id
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($user)
        ->postJson("/api/history-content", $data);

        foreach($data['history_content']["history_types"] as $type){
            $this->assertDatabaseHas("history_contentables", [
                "history_content_id" => $response["history_content"]["id"],
                "history_contentable_id" => $type["id"]
            ]);
        }
        $response->assertStatus(201);

    }



    private function prepare_seeds_for_sight_tests(){
        $this->seed();
        $this->seed([
            TestSightsSeeder::class
        ]);
    }
}
