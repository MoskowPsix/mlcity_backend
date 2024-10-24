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
        $image = new FileType();
        $image->name = 'image';
        $image->save();

        $video = new FileType();
        $video->name = 'video';
        $video->save();

        $link = new FileType();
        $link->name = 'link';
        $link->save();
    }
}
