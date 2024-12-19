<?php

namespace App\Jobs;

use App\Mail\HistoryContentChanged;
use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
use App\Notifications\ChangedHistoryContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSendMailOfChangedHistoryContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Event | Sight $content;
    /**
     * Create a new job instance.
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
    public function tags(): array
    {
        return ['send_mail', 'notification', 'changed_history_content', 'changed_history_content:' . $this->content->id];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = $this->getUsersEmailFavorite();
        $name = $this->content->name;
        $this->sendNotificationAboutChanges($users, $name);
    }

    private function getUsersEmailFavorite(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->content->favoritesUsers()->get();
    }

    private function sendNotificationAboutChanges(\Illuminate\Database\Eloquent\Collection $users, string $name){
        foreach($users as $user) {
            $user->notify(new ChangedHistoryContent($name));
        }
    }
}
