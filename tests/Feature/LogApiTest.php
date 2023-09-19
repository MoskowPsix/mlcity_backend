<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogApiTest extends TestCase
{
    use RefreshDatabase;
    private $user_1 = [
        'email' => '123n@mail.ru',
        'password' => 'Qwerty123',
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_log()
    {
        $this->seed();
        $response = $this->postJson('/api/login', $this->user_1);

        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);
        $user_id = $response->baseResponse->original['user']->id;

        $response = $this->getJson('/api/logs?user_id=' . $user_id);
        
        $response->assertJson([
            'status' => 'success',
            'logs' => [
                'data' => [
                    0 => [
                        'id' => 7,
                        'user_id' => $user_id
                    ]
                ]
            ]
        ]);
    }
}
