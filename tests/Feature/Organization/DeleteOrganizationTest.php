<?php

namespace Tests\Feature\Organization;

use App\Models\Sight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteOrganizationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_organization_delete():void
    {
        $user = User::factory()->create();
        $sight = Sight::factory()->create(['user_id' => $user->id]);
        $org = $sight->organization()->create();

        $response = $this->actingAs($user)->deleteJson(route('organization.delete', ['id' => $org->id]));

        $response->assertStatus(200);
    }

    public function test_organization_delete_not_found():void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->deleteJson(route('organization.delete', ['id' => 555]));
        $response->assertStatus(400);
    }

    public function test_organization_delete_not_author():void
    {
        $user = User::factory()->create();
        $sight = Sight::factory()->create(['user_id' => $user->id]);
        $org = $sight->organization()->create();
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->deleteJson(route('organization.delete', ['id' => $org->id]));
        $response->assertStatus(400);
    }
}
