<?php

namespace App\Jobs;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProccessCheckAndSendNotifyUsersStartEventfavorite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Event $event;
    /**
     * Create a new job instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function tags(): array
    {
        return ['notification', 'check_and_send', 'event_favorite_start:' . $this->event->id];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();
        $event_start = Carbon::parse($this->event->date_start);
        $favorite_time = $event_start->subHours(24);
        dd($favorite_time);
        if($event_start) {}
    }
}
