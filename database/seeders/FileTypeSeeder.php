<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FileType;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type1 = new FileType();
        $type1->name = 'image';
        $type1->save();

        $type1 = new FileType();
        $type1->name = 'video';
        $type1->save();

        $type1 = new FileType();
        $type1->name = 'link';
        $type1->save();
    }
}
