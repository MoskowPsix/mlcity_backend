<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Register extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_basic_user_register()
    {
        Mail::fake();

        $testUser = User::factory()->make();

        $response = $this->postJson('/api/register', [
            "name" => $testUser->name,
            "email" => $testUser->email,
            "password" => "verysecretpassiamrealysure",
            "password_confirmation" => "verysecretpassiamrealysure"
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas("users", [
            "name" => $testUser->name,
            "email" => $testUser->email,
            'email_verified_at' => null
        ]);
    }

    public function test_user_register_without_email()
    {
        $testUser = User::factory()->make();

        $response = $this->postJson('/api/register', [
            "name" => $testUser->name,
            "password" => "verysecretpassiamrealysure",
            "password_confirmation" => "verysecretpassiamrealysure"
        ]);

        $response->assertStatus(422);
    }

    public function test_user_register_without_name()
    {
        $testUser = User::factory()->make();

        $response = $this->postJson('/api/register', [
            "password" => "verysecretpassiamrealysure",
            "password_confirmation" => "verysecretpassiamrealysure"
        ]);

        $response->assertStatus(422);
    }

    public function test_user_register_with_empty_data()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
    }
}
