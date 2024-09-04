<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\EventObjectFactory;
use Tests\TestCase;

class GettingEventsTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_get_event_for_feed()
    {
        EventObjectFactory::createEventInDB(100, true);

        $response = $this->get("/api/events");

        $response->assertJsonStructure([
            "events" => [
                "data" => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'date_end',
                        'date_start',
                        'age_limit',
                        'files',
                        'price',
                        'types'
                    ]
                ]
            ]
        ])
        ->assertJsonFragment([
            "status" => "success",
        ]);
    }

    public function test_get_event_by_id()
    {
        EventObjectFactory::createEventInDB(1);

        $event = Event::first();
        $response = $this->get('/api/events/'.$event->id);

        $response->assertJsonStructure([
            'status',
            'message',
            'event' => [
                'id',
                'name',
                'description',
                'date_end',
                'date_start',
                'age_limit',
                'files',
                'price',
                'types',
                'statuses'
            ]
        ])
        ->assertJsonFragment([
            "status" => "success"
        ]);
    }

    public function test_get_event_by_non_existent_id()
    {
        EventObjectFactory::createEventInDB(1);

        $event = Event::first();
        $response = $this->get('/api/events/190912123'.$event->id);

        $response->assertStatus(404);
    }

    public function test_get_event_by_broken_string_id()
    {
        $response = $this->get('/api/events/buka_baka');
        $response->assertStatus(302);
    }
}
