<?php

namespace App\Listeners\Event;

use App\Events\Event\EventCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateHistoryContent
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
     * @param  \App\Events\EventCreated  $event
     * @return void
     */
    public function handle(EventCreated $event)
    {
        $event = $event->model;

        $historyContent = $event->historyContents()->create($event->toArray());
        
        foreach($event->places as $place){
           info($place);
        }

    }
}
