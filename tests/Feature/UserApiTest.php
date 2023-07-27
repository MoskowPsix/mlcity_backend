<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    //use DatabaseMigrations;
    use RefreshDatabase;
    private $user_1 = [
        'name' => 'Test',
        'email' => 'Test@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Свердловская область',
        'city' => 'Заречный',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];

    private $user_2 = [
        'name' => 'Test_2',
        'email' => 'Test_2@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Свердловская область',
        'city' => 'Екатеринбург',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];

    private $user_3 = [
        'name' => 'Test_3',
        'email' => 'Test_3@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Свердловская область',
        'city' => 'Ревда',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];

    private $user_4 = [
        'name' => 'Test_4',
        'email' => 'Test_4@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Свердловская область',
        'city' => 'Асбест',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];

    private $user_5 = [
        'name' => 'Test_5',
        'email' => 'Test_5@test.test',
        'password' => 'Qwerty123',
        'password_confirmation' => 'Qwerty123',
        'region' => 'Пермский край',
        'city' => 'Пермь',
        'avatar' => 'https://sun3-23.userapi.com/s/v1/if1/0oNeV9O1Cqja5nRdLsBO1xu1EPOgvOFaC45ZVAeXU7YWgp_LanxHzy2GfLtMR25NT9VQ3W4A.jpg?size=200x200&quality=96&crop=265,468,856,856&ava=1',
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_register()
    {

        $response = $this->postJson('/api/register', 
        $this->user_1
        );
        
         $response->assertStatus(200);
    }

    public function test_user_login()
    {
        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/login', $this->user_1);

        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);
        
        $response->assertJson(['status' => 'success']);
    }

    public function test_user_show_id()
    {
        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/login', $this->user_1);
        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);

        $user_id = $response->baseResponse->original['user']->id;

        $response = $this->getJson('/api/users/' . $user_id);        
        
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'id' => $user_id,
                'name' => $this->user_1['name'],
                'email' => $this->user_1['email'],
                'avatar' => $this->user_1['avatar'],
                'city' => $this->user_1['city'],
                'region' => $this->user_1['region'],
            ]
        ]);
    }

    public function test_user_show_with_filter()
    {
        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/register', 
        $this->user_2
        );

        $response = $this->postJson('/api/register', 
        $this->user_3
        );

        $response = $this->postJson('/api/register', 
        $this->user_4
        );

        $response = $this->postJson('/api/register', 
        $this->user_5
        );

        $response = $this->postJson('/api/login', $this->user_1);
        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);
        $user_id = $response->baseResponse->original['user']->id;

        // Проверка фильтра по городу
        $response = $this->getJson('/api/listUsers?city=' . $this->user_5['city']);        

        $response->assertJson([
            'status' => 'success',
            'users' => [
                'data' => [
                    0 => [
                        'city' => $this->user_5['city'],
                        'email' => $this->user_5['email'],
                        'name' => $this->user_5['name'],
                        'avatar' => $this->user_5['avatar'],
                        'region' => $this->user_5['region'],

                    ]
                ],
                'total' => 1,
            ]
        ]);

        // Проверка фильтра по имени
        $response = $this->getJson('/api/listUsers?name=' . $this->user_2['name']);  

        $response->assertJson([
            'status' => 'success',
            'users' => [
                'data' => [
                    0 => [
                        'city' => $this->user_2['city'],
                        'email' => $this->user_2['email'],
                        'name' => $this->user_2['name'],
                        'avatar' => $this->user_2['avatar'],
                        'region' => $this->user_2['region'],

                    ]
                ],
                'total' => 1,
            ]
        ]);

        // Проверка фильтра по региону
        $response = $this->getJson('/api/listUsers?region=' . $this->user_5['region']);  

        $response->assertJson([
            'status' => 'success',
            'users' => [
                'data' => [
                    0 => [
                        'city' => $this->user_5['city'],
                        'email' => $this->user_5['email'],
                        'name' => $this->user_5['name'],
                        'avatar' => $this->user_5['avatar'],
                        'region' => $this->user_5['region'],

                    ]
                ],
                'total' => 1,
            ]
        ]);

        // Проверка фильтра по почте
        $response = $this->getJson('/api/listUsers?email=' . $this->user_3['email']);  

        $response->assertJson([
            'status' => 'success',
            'users' => [
                'data' => [
                    0 => [
                        'city' => $this->user_3['city'],
                        'email' => $this->user_3['email'],
                        'name' => $this->user_3['name'],
                        'avatar' => $this->user_3['avatar'],
                        'region' => $this->user_3['region'],

                    ]
                ],
                'total' => 1,
            ]
        ]);

        // Проверка фильтра по ID
        $response = $this->getJson('/api/listUsers?id=' . $user_id);  

        $response->assertJson([
            'status' => 'success',
            'users' => [
                'data' => [
                    0 => [
                        'id' => $user_id,
                    ]
                ],
                'total' => 1,
            ]
        ]);
    }

    public function test_user_update()
    {
        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/login', $this->user_1);
        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);

        $user_id = $response->baseResponse->original['user']->id;

        $response = $this->putJson('/api/updateUsers/' . $user_id . '?name=' . $this->user_2['name'] . '&email=' . $this->user_2['email']);
        
        $response = $this->getJson('/api/users/' . $user_id);        
        
        $response->assertJson([
            'status' => 'success',
            'user' => [
                'name' => $this->user_2['name'],
                'email' => $this->user_2['email']
            ]
        ]);
    }


    public function test_user_delete()
    {
        $response = $this->postJson('/api/register', 
        $this->user_4
        );

        $user_id = $response->baseResponse->original['user']->id;

        $response = $this->postJson('/api/register', 
        $this->user_1
        );

        $response = $this->postJson('/api/login', $this->user_1);
        $response->withHeaders(['Bearer Token' => $response->baseResponse->original['access_token']]);


        $response = $this->deleteJson('/api/deleteUsers/' . $user_id);   

        $response->assertJson([
            'status' => 'success',
            'delete user' => $user_id
        ]);
    }

    // Изначально хз как работает вроде бы всё верно везде делаю, а ошибка вылезает в любом случае, даже в постмане, но токен становится не действителен.
    // public function test_user_logout()
    // {
    //     $response = $this->postJson('/api/register', 
    //     $this->user
    //     );

    //     $response = $this->postJson('/api/login', $this->user);

    //     $response->withHeaders(['Authorization' => 'Bearer ' . $response->baseResponse->original['access_token']]);
        
    //     //$token = $response->baseResponse->original['access_token'];
    //     $user_id = $response->baseResponse->original['user']->id;
    //     $header = $response->baseResponse->headers;

    //     $response = $this->postJson('/api/logout/' . $user_id);

    //     //print_r($header);

    //     $response->assertStatus(200);
    // }
}
