<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private $user_root = [
        'id' => 1,
        'email' => '123n@mail.ru',
        'password' => 'Qwerty123'
    ];
    private $role_1 = ['name'=>'root'];
    private $role_2 = ['name'=>'Admin'];
    private $role_3 = ['name'=>'Moderator'];

    private $role_add = [
        'name'=>'test role'
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_roles()
    {
        $this->seed();
        
        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->getJson('/api/allRole');

        $response->assertJson([$this->role_1,$this->role_2,$this->role_3,]);
    }

    public function test_get_role_by_id()
    {
        $this->seed();
        
        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $role_id = Role::all()[0]->id;
        $role = Role::where('id', $role_id)->firstOrFail();
        $role_name = $role->name;

        $response = $this->getJson('/api/getRole/'.$role_id);
        $response->assertJson(['status'=>'success','role'=>["name"=>$role_name]]);
    }

    public function test_create_role()
    {
        $this->seed();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $response = $this->postJson('/api/addRole',$this->role_add);
        $response->assertJson(['status'=>'success','role'=>$this->role_add]);
    }

    public function test_update_role()
    {
        $this->seed();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $role_id = Role::all()[0]->id;

        $response = $this->putJson('/api/updateRole/'.$role_id,['name'=>'test root']);

        $role = Role::where('name','test root')->firstOrFail();


        $response->assertJson(['status'=>'SUCCESS', 'role'=>['id'=>$role->id, 'name'=>$role->name]]);
    }
    
    public function test_delete_role()
    {
        $this->seed();

        $auth = $this->postJson('/api/login',$this->user_root);
        $auth->withHeaders(['Bearer Token'=>$auth->baseResponse->original['access_token']]);

        $role_id = Role::where('name','Moderator')->firstOrfail()->id;
        $user_id = User::where('name','Test_user')->firstOrFail()->id;
        

        $response = $this->deleteJson('/api/deleteRoleUser/'.$user_id.'/'.$role_id);
        $response->assertJson(['status'=>'success','user'=>$user_id, 'delete_role'=>$role_id]);


    }
}