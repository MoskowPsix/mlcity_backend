<?php

namespace Tests\Feature\Organization;

use App\Models\Event;
use App\Models\Sight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GettingOrganizationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_get_events_in_sight()
    {
        $sight = Sight::factory()->create();
        $org = $sight->organization()->create();

        $events = Event::factory()->count(5)->create([
            "organization_id" => $org->id
        ]);

        $response = $this->getJson('api/organizations/'.$org->id.'/events');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "events" => [
                "data" => [
                    "*" => [
                        'afisha7_id',
                        'age_limit',
                        'id',
                        'date_end',
                        'date_start',
                        'description',
                        'materials',
                        'name',
                        'organization_id'
                    ]
                ]
            ]
        ]);

        foreach($events as $event)
        {
            $response->assertJsonFragment([
                "id" => $event->id
            ]);
        }
    }


}
