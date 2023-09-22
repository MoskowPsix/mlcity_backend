<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventType;
use Database\Seeders\test\TestEventSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTypeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private $user_root = [
        'id' => 1,
        'email' => '123n@mail.ru',
        'password' => 'Qwerty123'
        ];

    public function test_get_all_event_types()
    {
        $this->seed();
        $event_types = EventType::all()->toArray();

        $response = $this->getJson('/api/event-types');
        $response->assertJson(['status'=>'success','types'=>$event_types]);
    }

    public function test_get_event_type_by_id()
    {
        $this->seed();

        $event_type = EventType::where('name','Деловые')->first();
        

        $response = $this->getJson('/api/events/getTypesId/'.$event_type->id);
        $response->assertJson(['status'=>'success','types'=>$event_type->toArray()]);
    }

    public function test_add_type_to_event()
    {
        $this->seed();
        $this->seed(TestEventSeeder::class);

        $event = Event::where('name','Встреча')->first();
        $event_type = EventType::where('name','Деловые')->first();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->postJson('/api/events/addTypeEvent/'.$event->id.'/'.$event_type->id);
        $response->assertJson(['status'=>'success','event'=>$event->id, 'add_type'=>$event_type->id]);
    }

    public function test_update_event_type()
    {
        $this->seed();
        $this->seed(TestEventSeeder::class);

        $event = Event::where('name','Встреча')->first();
        $event_type1 = EventType::where('name','Деловые')->first();
        $event_type2 = EventType::where('name','Благотворительные')->first();
        $event->types()->sync($event_type1->id);

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->putJson('/api/events/updateTypeEvent/'.$event->id.'/'.$event_type2->id);
        $response->assertJson(['status'=>'success','event'=>$event->id, 'update_type'=>$event_type2->id]);
    }


}
