<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root = Role::where('name','root')->first();
        $admin = Role::where('name','Admin')->first();
        $moderator = Role::where('name','Moderator')->first();

        $su = new User();
        $su->name = 'Admin';
        $su->email = '123n@mail.ru';
        $su->password = bcrypt('Qwerty123');
        $su->save();
        $su->roles()->attach($root); //добавляем юзеру роль


        $su2 = new User();
        $su2->name = 'Test_user';
        $su2->email = '123@mail.ru';
        $su2->password = bcrypt('qwerty');
        $su2->save();
        $su2->roles()->attach($moderator);
    }
}
