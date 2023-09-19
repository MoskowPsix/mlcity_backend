<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\StatusesSeeder;
use App\Models\Status;
use App\Models\User;
use App\Models\Event;
use App\Models\Sight;

class StatusTest extends TestCase
{
   use RefreshDatabase;

   private $statuse_1 = ['name'=>'Опубликовано'];
   private $statuse_2 = ['name'=>'Отказ'];
   private $statuse_3 = ['name'=> 'Черновик'];
   private $statuse_4 = ['name'=> 'На модерации'];
   private $statuse_5 = ['name'=>'В архиве'];

   private $user_root = [
    'id' => 1,
    'email' => '123n@mail.ru',
    'password' => 'Qwerty123'
    ];

   private $status_event = [
    'event_id'=>1,
    'status_id'=>1,
    'descriptions'=>'Test case'
   ];
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_statuses()
    {
        $this->seed(StatusesSeeder::class);
        $response = $this->getJson('/api/statuses');

        $response->assertJson(['status'=>'success','statuses'=>[$this->statuse_1,$this->statuse_2,
            $this->statuse_3,$this->statuse_4,$this->statuse_5]]);


    }

    public function test_get_status_by_id()
    {
        $this->seed(StatusesSeeder::class);


        $response = $this->getJson('/api/getStatusId/'.'41');

        $response->assertJson(["status"=>'success', "statuses"=>$this->statuse_1]);
    }

    public function test_add_status_event()
    {
        
        
        $this->seed();
        

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->postJson('/api/events/addStatusEvent',['event_id'=>Event::all()[0]->id,'status_id'=>Status::all()[0]->id,'descriptions'=>'Test case']);

        $response->assertJson(['status'=>'success','event'=>Event::all()[0]->id,'add_status'=>Status::all()[0]->id,'descriptions'=>'Test case']);
    }

    public function test_add_status_sight()
    {
        $this->seed();

        echo Sight::all();

        $sight = Sight::where('name','Клуб Лайм')->firstOrFail();
        $event = Event::where('name','Встреча')->firstOrFail();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);
        

        $response = $this->postJson('/api/events/addStatusSight',['status_id'=>$event->id,
            'sight_id'=>$sight->id,'descriptions'=>'test descriptions']);

        $response->assertJson(['status'=>'success','sight'=>$sight->id, 'add_status'=>$event->id]);


    }
}
