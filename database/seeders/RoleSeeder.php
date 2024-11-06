<?php

namespace Database\Seeders;

use App\Constants\RolesConstants;
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
        $roles = RolesConstants::getConstants();
        foreach($roles as $role) {
            Role::create(["name" => $role]);
        }
    }
}
