<?php

namespace App\Listeners\Notify;

use App\Events\Notify\StartHoursEventNotify;
use App\Models\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StartHoursListenersNotify implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        Notify::create([
            'user_id' => $event->user->id,
            'private' => true,
            'message' => __('messages.notify.private.event.started_day'),
            'data' => json_encode($event->model)
        ]);
    }
}
