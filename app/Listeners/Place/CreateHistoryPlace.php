<?php

namespace App\Listeners\Place;

use App\Events\Place\PlaceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateHistoryPlace
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Place\PlaceCreated  $event
     * @return void
     */
    public function handle(PlaceCreated $event)
    {
        $place = $event->model;
        $historyContent = $place->event->historyContents->first();
        // $historyPlace = $historyContent->historyPlaces()->create($place->toArray());

    }
}
