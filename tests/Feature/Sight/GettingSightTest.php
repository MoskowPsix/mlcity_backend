<?php

namespace Tests\Feature\Sight;

use App\Models\Event;
use App\Models\Sight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GettingSightTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_sight_by_id()
    {
        $sight = Sight::first();
        $response = $this->getJson('api/sights/' . $sight->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "status",
            "message",
            "sight" => [
                "id",
                "name",
                "files"
            ]
        ]);

        $response->assertJsonFragment([
            "name" => $sight->name,
            "id" => $sight->id,
            "files" => $sight->files
        ]);
    }

    public function test_get_sgiht_by_non_existent_id()
    {
        Sight::factory()->create();
        $response = $this->getJson('/api/sights/190912123');

        $response->assertStatus(404);
    }

    public function test_get_sight_by_broken_string_id()
    {
        $response = $this->getJson('/api/sights/buka_baka');
        $response->assertStatus(422);
    }
}
