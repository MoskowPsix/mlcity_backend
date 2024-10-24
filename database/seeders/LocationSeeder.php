<?php

namespace Database\Seeders;

use App\Models\Location;
use JeroenZwart\CsvSeeder\CsvSeeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends CsvSeeder
{
    public function __construct()
    {
    $this->file = './database/seeders/csvs/locations.csv';
    $this->tablename = 'locations';
    $this->delimiter  = ',';
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        parent::run();
    }
}
