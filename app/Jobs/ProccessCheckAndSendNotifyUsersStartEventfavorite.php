<?php

namespace App\Jobs;

use App\Events\Notify\StartHoursEventNotify;
use App\Models\Event;
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
        $this->event->favoritesUsers()->chunk(1000, function ($users_ch) {
            $users_ch->each(function($user) {
                dump($user->name);
//              $user->notify(new VerifyEmail());

                event((new StartHoursEventNotify($this->event, $user)));
            });
        });
    }
}
