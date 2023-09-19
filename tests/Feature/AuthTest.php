<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private $user_1 = [
        'name' => 'Test',
        'email' => 'Test@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];

    private $user_1_reset_pass = [
        'old_password'=> 'Qwerty123',
        'new_password' => 'Qwerty1234',
        'retry_password' => 'Qwerty1234',
    ];
    private $user_1_reset_pass_user = [
        'id'=>1,
        'new_password' => 'Qwerty1234',
        'retry_password' => 'Qwerty1234',
    ];

    private $user_root = [
        'id' => 1,
        'email' => '123n@mail.ru',
        'password' => 'Qwerty123'
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_reset()
    {
        $response = $this->postJson('/api/register',
            $this->user_1);

        $response = $this->postJson('/api/login',
            $this->user_1);

        $response = $this->putJson('/api/reset_password',
            $this->user_1_reset_pass);

        $response->assertJson(['status'=>'success','message' => 'Password changed!']);
    }

    public function test_password_reset_user()
    {
        $this->seed();

        $response = $this->postJson('/api/register',
            $this->user_1);

        $auth = $this->postJson('/api/login',
            $this->user_root);
        $auth->withHeaders(['Bearer Token' => $auth->baseResponse->original['access_token']]);
        
        $response = $this->putJson('/api/reset_password_user',
            $this->user_1_reset_pass_user);

        $response->assertJson(['status'=>'success','message'=>'Password changed!']);






    }
}
