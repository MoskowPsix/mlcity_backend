<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogApiTest extends TestCase
{
    use RefreshDatabase;
    private $user_1 = [
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
    public function test_get_log()
    {
        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/login', $this->user_1);

        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);
        $user_id = $response->baseResponse->original['user']->id;

        $response = $this->getJson('/api/logs?user_id=' . $user_id);
        
        $response->assertJson([
            'status' => 'success',
            'logs' => [
                'data' => [
                    0 => [
                        'id' => 2,
                        'user_id' => $user_id
                    ]
                ]
            ]
        ]);
    }
}
