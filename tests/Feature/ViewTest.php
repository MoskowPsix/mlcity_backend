<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Sight;
use Database\Seeders\test\TestEventSeeder;
use Database\Seeders\test\TestLocationSeeder;
use Database\Seeders\test\TestSightsSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     private $user_root = [
        'email' => '123n@mail.ru',
        'password' => 'Qwerty123'
    ];

    public function test_add_view_to_event()
    {
        $this->seed();
        $this->seed(TestEventSeeder::class);

        $event = Event::where('name','Встреча')->firstOrFail();

        $user = $this->postJson('/api/login',$this->user_root);

        $response = $this->postJson('/api/events/view', ['time'=>4,'event_id'=>$event->id]);
        $response->assertJson(['status'=>'success']);
    }

    public function test_add_view_to_sight()
    {
        $this->seed();
        $this->seed(TestLocationSeeder::class);
        $this->seed(TestSightsSeeder::class);

        $sight = Sight::where('name','Клуб Лайм')->firstOrFail();

        $user = $this->postJson('/api/login',$this->user_root);

        $response = $this->postJson('/api/events/view', ['time'=>4,'sight_id'=>$sight->id]);
        $response->assertJson(['status'=>'success']);
    }


}
