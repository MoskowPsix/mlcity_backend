<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function getAll(): \Illuminate\Http\JsonResponse
    {
        $events = Event::all();

        return response()->json([
            'status' => 'success',
            'events' => $events
        ], 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        dd('show');
    }

    public function create(EventCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $event = Event::create([
            'name'          => $request->name,
            'sponsor'       => $request->sponsor,
            'address'       => $request->address,
            'coords'        => $request->coords,
            'description'   => $request->description,
            'price'         => $request->price,
            'materials'     => $request->materials,
            'date_start'    => $request->dateStart,
            'date_end'      => $request->dateEnd,
            'user_id'       => Auth::user()->id,
        ]);

        $event->types()->sync($request->type);
        $event->statuses()->attach($request->status);

        if ($request->vkFiles){
            $this->saveVkFiles($event, $request->vkFiles);
        }

        if ($request->localFiles){
            $this->saveLocalFiles($event, $request->localFiles);
        }

        return response()->json([
            'event-id' => $event->id,
            'files' => $request->localFiles,
            'vk-files' => $request->vkFiles
        ], 200);
    }

    public function update($id): \Illuminate\Http\JsonResponse
    {
        dd('update');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        dd('delete');
    }

    private function saveVkFiles($event, $files){
        foreach ($files as $file) {
            $event->files()->create([
                "name" => uniqid('img_'),
                "link" => $file,
            ]);
        }
    }

    private function saveLocalFiles($event, $files){

        foreach ($files as $file) {
            $filename = uniqid('img_').$file->getClientOriginalName();

            $path = $file->store('events/'.$event->id, 'public');

            $event->files()->create([
                "name" => $filename,
                "link" => '/storage/'.$path,
            ]);

        }

    }
}
