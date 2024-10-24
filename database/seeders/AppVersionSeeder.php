<?php

namespace Database\Seeders;

use App\Models\AppVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppVersion::create([
            'platform' => 'android',
            'version' => '1.0.0',
        ]);
        AppVersion::create([
            'platform' => 'ios',
            'version' => '1.0.0',
        ]);
        AppVersion::create([
            'platform' => 'rustore',
            'version' => '1.0.0',
        ]);
    }
}
