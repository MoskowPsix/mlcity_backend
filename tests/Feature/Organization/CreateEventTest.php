<?php

namespace Tests\Feature\Organization;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $user = User::first();
        $organization = Organization::create([
            "name" => "Alex Studio",
            "description" => "test",
            "user_id" => $user->id
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_event_without_permission()
    {
        $user = User::first();
        $organization = Organization::first();
        $response = $this->actingAs($user)->post('/api/events/create', [
            "organization_id" =>$organization->id,
        ]);
        $response->assertStatus(403);
    }

    public function test_create_event_with_permission()
    {
        $user = User::first();
        $organization = Organization::first();
        $perm = Permission::where('name', 'create_content')->get();
        dd($user->permissionsInOrganization()->where("organization_id", $organization->id)->get());
        $user->permissionsInOrganization()->where("organization_id", $organization->id)->toggle([$perm->id => ["organization_id" => $organization->id]]);

        $response = $this->actingAs($user)->post('/api/events/create', [
            "organization_id" =>$organization->id,
        ]);
        $response->assertStatus(403);
    }
}
