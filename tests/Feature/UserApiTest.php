<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseMigrations;
    //use RefreshDatabase;
    private $user = [
        'name' => 'Test',
        'email' => 'Test@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Свердловская область',
        'city' => 'Заречный',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_create()
    {

         $response = $this->postJson('/api/register', $this->user);
        
         $response->assertStatus(200);
    }
}
