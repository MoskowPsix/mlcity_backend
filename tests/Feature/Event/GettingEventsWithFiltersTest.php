<?php

namespace Tests\Feature\Event;

use App\Http\Resources\Event\EventResource;
use App\Http\Resources\Event\GetEvents\SuccessGetEventsResource;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GettingEventsWithFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_event_filter_order(): void
    {
        Event::factory()->count(6)->create();
        $events = $this->getEvents();
        $response = $this->getJson(route('events.get_all', ['order' => 'name']));
        $response->assertExactJson($events);
    }
    public function test_get_event_filter_name(): void
    {
        $events = Event::factory()->count(6)->create();
        $response = $this->getJson(route('events.get_all', ['name' => $events[5]->name]));
        $response->assertJsonFragment([
            'name' => $events[5]->name,
        ]);
    }
    public function test_get_event_filter_ids(): void
    {
        $events = Event::factory()->count(6)->create();
        $response = $this->getJson(route('events.get_all', ['eventIds' => $events[0]->id.','.$events[1]->id]));
        $response->assertJsonFragment([
            'id' => $events[0]->id,
            'id' => $events[1]->id,
        ]);
    }
    private function getEvents(): array
    {
        $events = Event::query()
            ->orderByDesc('name')
            ->with('files', 'author', "types", 'price', 'statuses',)
            ->withCount('likedUsers', 'favoritesUsers', 'comments')
            ->cursorPaginate(6, ['*'], 'page' , '');
        $event_resource = new SuccessGetEventsResource($events);
        return json_decode($event_resource->toJson(), true);
    }
}
