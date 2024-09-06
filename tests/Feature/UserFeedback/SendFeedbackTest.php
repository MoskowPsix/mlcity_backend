<?php

namespace Tests\Feature\UserFeedback;

use App\Mail\UserFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendFeedbackTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_feedback()
    {
        Mail::fake();
        $response = $this->postJson('feedback/user', [
            "name" => "Alex",
            "email" => "example@mail.ru",
            "text" => "IT Works"
        ]);

        $response->assertStatus(200);

        Mail::assertSent(UserFeedback::class, function ($email) {
            return $email->hasTo('example@mail.ru');
        });
    }
}
