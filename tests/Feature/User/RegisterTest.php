<?php

namespace Tests\Feature\User;

use App\Models\Sight;
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

    public function test_basic_user_register_with_avatar()
    {
        $testUser = User::factory()->make();

        $response = $this->postJson('/api/register', [
            "name" => $testUser->name,
            "email" => $testUser->email,
            "avatar" => "https://avatars.mds.yandex.net/i?id=b9f61175e689c320cd0ca6a78f09bd6bd7820393-12714516-images-thumbs&n=13",
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

    public function test_basic_user_register_without_passwords()
    {
        $testUser = User::factory()->make();

        $response = $this->postJson('/api/register', [
            "name" => $testUser->name,
            "email" => $testUser->email,
        ]);

        $response->assertStatus(422);
    }

    public function test_register_user_with_exist_email()
    {
        $user = User::first();

        $response = $this->postJson('/api/register', [
            "email" => $user->email,
            "password" => "verysecretpassiamrealysure",
            "password_confirmation" => "verysecretpassiamrealysure"
        ]);

        $response->assertStatus(422);
    }

    public function test_memory_allowed(): void
    {
        Sight::factory()->count(1000)->create()->each(function (Sight $sight) {
            $sight->types()->attach(1);
            $sight->types()->attach(2);
            $sight->types()->attach(2);
            $sight->types()->attach(2);
            $sight->types()->attach(2);
            $sight->types()->attach(3);
            $sight->types()->attach(4);
            $sight->types()->attach(5);
        });
        Sight::query()->orderBy('id')->chunk(10, function ($sight) {
            $sight->each(function($sight) {
                $types = $sight->types->pluck('id')->toArray();
                $sight->types()->detach($types);
                $sight->types()->attach(array_unique($types));
            });
        });
    }
}
