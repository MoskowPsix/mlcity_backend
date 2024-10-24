<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetEventsForAuthorTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_events_for_author(): void
    {
        $user = User::factory()->create();
        Event::factory()->count(6)->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->getJson(route('events.get_for_author'));
        $response->assertJsonStructure([
            'status',
            'message',
            'events' => [
                'data' => [
                    '*' => [
                        'id',
                    ]
                ]
            ]
        ]);
    }
    public function test_get_events_for_author_not_author(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user1->id]);
        $response = $this->actingAs($user2)->getJson(route('events.get_for_author'));
        $response->assertDontSeeText($event->name);
    }

    public function test_get_events_for_author_with_status_filter(): void
    {
        $publish = Status::where('name', 'Опубликовано')->first();
        $arhive = Status::where('name', 'В архиве')->first();
        $user = User::factory()->create();
        $event1 = Event::factory()->create(['user_id' => $user->id]);
        $event1->statuses()->attach($publish->id, ['last' => true]);
        $event2 = Event::factory()->create(['user_id' => $user->id]);
        $event2->statuses()->attach($arhive->id, ['last' => true]);
        $query = [
            'statuses' => 'Опубликовано',
            'statusLast' => 1,
        ];
        $response = $this->actingAs($user)->getJson(route('events.get_for_author', $query));
        $response->assertDontSeeText($event2->name);
    }
}
