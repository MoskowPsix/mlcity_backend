<?php

namespace Tests\Feature;

use App\Models\Sight;
use App\Models\SightType;
use Database\Seeders\test\TestLocationSeeder;
use Database\Seeders\test\TestSightsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SightTypeTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_get_sight_type()
    {
        $this->seed();
        $this->seed([TestSightsSeeder::class]);


        $types = SightType::all()->toArray();

        $response = $this->getJson('/api/sight-types');

        $response->assertJson((['status'=>'success','types'=>$types]));
    }

    public function test_get_sight_by_id()
    {
        $this->seed();
        $this->seed([TestSightsSeeder::class]);

        $sight_type_id = SightType::first()->id;
        $sight_type = SightType::where('id',$sight_type_id)->get()->toArray();

        $response = $this->getJson('/api/sights/getTypesId/'.$sight_type_id);
        $response->assertJson(['status'=>'success', 'types'=>$sight_type[0]]);


    }

    public function test_add_type_sight()
    {
        $this->seed();
        $this->seed([TestSightsSeeder::class]);

        $sight = Sight::where('name','Клуб Лайм')->first();
        $sight_type = SightType::where('name','Святыни')->first();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->postJson('/api/sights/addTypeSight/'.$sight->id.'/'.$sight_type->id);
        $response->assertJson(['status'=>'success','sight'=>$sight->id, 'add_type'=>$sight_type->id]);

    }

    public function test_update_type_sight()
    {
        $this->seed();
        $this->seed([TestSightsSeeder::class]);

        $sight = Sight::where('name','Клуб Лайм')->first();
        $sight_type1 = SightType::where('name','Святыни')->first();
        $sight_type2 = SightType::where('name','Спортивные')->first();
        $sight->types()->attach($sight_type1->id);

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->putJson('/api/sights/updateTypeSight/'.$sight->id.'/'.$sight_type2->id);
        $response->assertJson(['status'=>'success','sight'=>$sight->id,'update_type'=>$sight_type2->id]);
    }
}
