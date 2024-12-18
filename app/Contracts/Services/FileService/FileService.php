<?php

namespace App\Contracts\Services\FileService;

use App\Models\FileType;

class FileService {
    public function saveVkFilesImg($event, $files){
        $type = FileType::where('name', 'image')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ])->file_types()->attach($type[0]->id);
        }
    }
    public function saveVkFilesVideo($event, $files){
        $type = FileType::where('name', 'video')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('video_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    public function saveVkFilesLink($event, $files){
        $type = FileType::where('name', 'link')->get();
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('link_'),
                "link" => $file,
            ])->file_types()->sync($type[0]->id);
        }
    }
    public function saveLocalFilesImg($event, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_');
            $path = $file->store('events/'.$event->id, 'public');
            $type = FileType::where('name', 'image')->get();

            $event->files()->create([
                'name'  => $filename,
                'link'  => '/storage/'.$path,
                'local' => 1
            ])->file_types()->sync($type[0]->id);

        }

    }
}
