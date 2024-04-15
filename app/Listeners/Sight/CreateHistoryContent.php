<?php

namespace App\Listeners\Sight;

use App\Events\Sight\SightCreated;
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
     * @param  \App\Events\Sight\SightCreated  $event
     * @return void
     */
    public function handle(SightCreated $event)
    {
        $sight = $event->model;
        $data = $this->prepareSightData($sight);
        $sight->historyContents()->create($data);
    }

    public function prepareSightData($sight){
        $data = $sight->toArray();
        unset($data["id"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);

        return $data;

    }
}
