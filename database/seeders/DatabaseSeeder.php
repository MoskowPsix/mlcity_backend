<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        //$this->call(EventTypeSeeder::class);
        //$this->call(SightTypeSeeder::class);
        $this->call(StatusesSeeder::class);
        $this->call(FileTypeSeeder::class);
        $this->call(SightTypeSeeder::class);
        $this->call(EventTypeSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AgreementSeeder::class);
        $this->call(FavoriteCitySeeader::class);
        $this->call(TimezoneSeeder::class);
        $this->call(AppVersionSeeder::class);
        $this->call(SightSeeder::class);
        $this->call(OrganizationSeeder::class);
    }
}
