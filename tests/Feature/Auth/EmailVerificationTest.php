<?php

namespace Tests\Feature\Auth;

use App\Mail\OrderCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_сode_email()
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/verificationEmail');

        $response->assertStatus(200);
        Mail::assertSent(OrderCode::class, function ($email) use ($user) {
            return $email->hasTo($user->email);
        });
    }

    public function test_create_сode_email_cooldown()
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/verificationEmail');
        $response = $this->actingAs($user)->post('/api/verificationEmail');


        $response->assertStatus(400);
        $response->assertJsonFragment([
            "status" => "error"
        ]);
    }
}
