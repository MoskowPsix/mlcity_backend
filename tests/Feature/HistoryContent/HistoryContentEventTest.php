<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventType;
use App\Models\HistoryContent;
use App\Models\Location;
use App\Models\Place;
use App\Models\Price;
use App\Models\Timezone;
use App\Models\User;
use Database\Seeders\test\TestEventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class HistoryContentEventTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public $event;
    public $user;

     public function test_create_history_content_for_event_with_all_attrs(){
        $this->prepare_data_for_test();
        $data = [
            "id" => $this->event->id,
            "type" => "Event",

            "history_content" => [
                "name" => "new name",
                "sponsor" => "new sponsor",
                "description" => "new desc",
                "materials" => "new materials",
                "date_start" => "2005-05-27 00:00:00",
                "date_end" => "2005-05-28 00:00:00"
            ]
        ];

        $response = $this->actingAs($this->user)
        ->postJson("/api/history-content", $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data["history_content"]);
    }

    public function test_create_history_content_for_event_with_new_prices(){
        $this->prepare_data_for_test();
        $price1 = Price::factory()->make([
            "sight_id" => null,
            "event_id" => $this->event->id
        ]);
        $price2 = Price::factory()->make([
            "sight_id" => null,
            "event_id" => $this->event->id
        ]);
        $price3 = Price::factory()->make([
            "sight_id" => null,
            "event_id" => $this->event->id
        ]);


        $data = [
            "id" => $this->event->id,
            "type" => "Event",

            "history_content" => [
                "history_prices" => [
                    [
                        "cost_rub" => $price1->cost_rub,
                        "descriptions" => $price1->descriptions
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

        $response = $this->actingAs($this->user)
        ->postJson("/api/history-content",$data);

        $response->assertStatus(201);
        foreach($data["history_content"]["history_prices"] as $price){
            $this->assertDatabaseHas("history_prices", [
                "history_content_id" => $response["history_content"]["id"],
                "cost_rub" => $price["cost_rub"],
                "descriptions" => $price["descriptions"]
            ]);
        }
    }

    public function test_create_history_content_for_event_with_new_places() {
        $this->prepare_data_for_test();
        $testPlaces = Place::factory(20)->make()->toArray();
        $data = [
            "id" => $this->event->id,
            "type" => "Event",

            "history_content" => [
                "history_places" => [
                    ...$testPlaces
                ]
            ]
        ];

        $response = $this->actingAs($this->user)
        ->postJson("/api/history-content", $data);
        $response->assertStatus(201);

        foreach($testPlaces as $place) {
            $this->assertDatabaseHas("history_places", [
                "history_content_id" => $response["history_content"]["id"],
                "latitude" => $place["latitude"],
                "longitude" => $place["longitude"],
                "address" => $place["address"]
            ]);
        }
    }

    public function test_accept_history_content_with_standart_attrs() {
        $this->prepare_data_for_test();
        $historyContent = HistoryContent::create([
            "name" => "test",
            "user_id" => $this->user->id,
            "history_contentable_id" => Event::first()->id,
            "history_contentable_type" => "App\Models\Event",
            "sponsor" => "new sponsor",
            "description" => "new desc",
            "materials" => "new matter",
            "date_start" => "2005-05-27 00:00:00",
            "date_end" => "2005-05-28 00:00:00",
            "work_time" => "Thuesday",
            "address" => "new address"

        ]);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content", [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_accept_history_content_with_new_prices() {
        $this->prepare_data_for_test();
        $historyContent = $this->event->historyContents()->create(["user_id" => $this->user->id]);
        $historyContent->historyPrices()->create(["cost_rub" => 1400, "descriptions"=>"test"]);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content", [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("price", [
            "event_id" => $this->event->id,
            "cost_rub" => 1400,
            "descriptions" => "test"
        ]);
    }

    public function test_accept_history_content_with_new_places() {
        $this->prepare_data_for_test();
        $location = Location::first();

        $placeData = [
            "longitude" => 51,
            "latitude" => 37,
            "address" => "Lenina 16A",
            "location_id" =>$location->id
        ];

        $historyContent = $this->event->historyContents()->create(["user_id" => $this->user->id]);
        $historyContent->historyPlaces()->create($placeData);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content", [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("places", [
            "event_id" => $this->event->id,
            ...$placeData
        ]);
    }

    public function test_accept_history_content_edit_place() {
        $this->prepare_data_for_test();
        $place = $this->event->places()->create([
            "latitude" => 34,
            "longitude" => 51,
            "location_id" => Location::first()->id,
            "address" => "new address",
            "timezone_id" => Timezone::first()->id
        ]);


        $historyContent = $this->event->historyContents()->create(["user_id" => $this->user->id]);
        $historyContent->historyPlaces()->create([
            "place_id" => $place->id,
            "address" => "new address test"
        ]);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content",  [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("places", [
            "id" => $place->id,
            "address" => "new address test"
        ]);
    }

    public function test_accept_history_content_with_new_types() {
        $this->prepare_data_for_test();
        $types = EventType::where("name","Кино")->orWhere("name", "Спорт")->get();

        $historyContent = $this->event->historyContents()->create(["user_id" => $this->user->id]);
        foreach($types as $type) {
            $historyContent->historyEventTypes()->attach($type["id"]);
        }

        $this->assertDatabaseHas("history_contentables", ["history_content_id" => $historyContent->id]);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content", [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);

        foreach($types as $type) {
            $this->assertDatabaseHas("events_etypes", [
            "event_id" => $this->event->id,
            "etype_id" => $type["id"]
            ]);
        }
    }

    public function test_accept_delete_history_content_type() {
        $this->prepare_data_for_test();

        $types = EventType::where("name","Кино")->orWhere("name", "Спорт")->get()->pluck("id");

        $historyContent = $this->event->historyContents()->create(["user_id" => $this->user->id]);
        foreach($types as $type) {
            $this->event->types()->attach($type);
        }

        $historyContent->historyEventTypes()->attach($types[0], ["on_delete" => true]);

        $response = $this->actingAs($this->user)
        ->patchJson("/api/history-content", [
            "status" => "Опубликовано",
            "historyContent" => [
                "id"=> $historyContent->id
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing("events_etypes", [
            "event_id" => $this->event->id,
            "etype_id" => $types[0]
        ]);
    }



    private function prepare_seeds_for_event_tests(){
        $this->seed();
        $this->seed([
            TestEventSeeder::class
        ]);
    }

    private function prepare_data_for_test() {
        $this->prepare_seeds_for_event_tests();
        $this->user = User::first();
        $this->event = Event::first();
    }


}
