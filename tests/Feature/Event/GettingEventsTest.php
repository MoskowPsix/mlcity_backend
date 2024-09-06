<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\Organization;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\EventObjectFactory;
use Tests\TestCase;

class GettingEventsTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

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

        $response = $this->get('/api/events/190912123');

        $response->assertStatus(404);
    }

    public function test_get_event_by_broken_string_id()
    {
        $response = $this->getJson('/api/events/buka_baka');
        $response->assertStatus(422);
    }

    public function test_get_author_events()
    {
        $user = User::first();
        Event::factory()->count(10)->create([
            "user_id" => $user->id
        ]);
        $response = $this->actingAs($user)->getJson('/api/events-for-author');

        $response->assertStatus(200);
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
                    ],
                    "next_cursor",
                    "prev_cursor"
            ]
        ]);
    }

    public function test_check_user_have_event_in_favorite()
    {
        $user = User::first();
        $event = Event::factory()->create();

        $user->favoriteEvents()->toggle($event->id);

        $response = $this->actingAs($user)->getJson('/api/events/'.$event->id.'/check-user-favorite');

        $response->assertJsonFragment([
            "is_favorite" => true
        ]);
    }

    public function test_check_user_dont_have_event_in_favorite()
    {
        $user = User::first();
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/events/'.$event->id.'/check-user-favorite');

        $response->assertJsonFragment([
            "is_favorite" => false
        ]);
    }


    public function test_get_organization_by_event()
    {
        $sight = Sight::factory()->create();
        $organization = $sight->organization()->create();
        $event = Event::factory()->create([
            "organization_id" => $organization->id
        ]);

        $response = $this->getJson('/api/events/'.$event->id.'/organization');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "organization" => [
                "id",
                "name",
                "files"
            ]
        ]);
    }

    // TODO: Compleat this test

    // public function test_get_expired_events()
    // {
    //     $events = Event::factory()->count(10)->create()->map(function () {
    //         $dateStart = fake()->dateTimeBetween('-3 day', 'now');
    //         $dateEnd = fake()->dateTimeBetween($dateStart, '+10 day'); // Дата окончания не раньше даты начала
    //         info([$dateStart, $dateEnd]);
    //         return [
    //             "date_start" => $dateStart,
    //             "date_end" => $dateEnd,
    //         ];
    //     });;

    //     $response = $this->getJson("/api/events?expired=false");
    // }
}
