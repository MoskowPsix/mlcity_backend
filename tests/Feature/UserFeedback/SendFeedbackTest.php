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
    public function test_send_feedback(): void
    {
        Mail::fake();
        $data = [
            "name" => "Alex",
            "email" => "example@mail.ru",
            "text" => "it's Work mail method"
        ];
        $response = $this->postJson(route('feedback.user'), $data);
        $response->assertStatus(200);
        Mail::assertSent(UserFeedback::class, function ($email) use($data){
            return $email->data['email'] === $data['email'];
        });
    }
}
