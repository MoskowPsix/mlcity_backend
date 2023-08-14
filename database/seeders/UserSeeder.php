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
        $admin = Role::where('name','Admin')->first();
        $moderator = Role::where('name','Moderator')->first();

        $su = new User();
        $su->name = 'Admin';
        $su->email = '123n@mail.ru';
        $su->password = bcrypt('Qwerty123');
        $su->save();
        $su->roles()->attach($admin); //добавляем юзеру роль
    }
}
