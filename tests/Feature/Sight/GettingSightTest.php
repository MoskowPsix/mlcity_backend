<?php

namespace Tests\Feature\Sight;

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
}
