<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $admin = new Role();
        $admin->name = 'Admin';
        $admin->save();

        $moderator = new Role();
        $moderator->name = 'Moderator';
        $moderator->save();
    }
}
