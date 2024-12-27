<?php

namespace App\Events\Notify;

use App\Models\Event;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartHoursEventNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Event $model;
    public User $user;
    /**
     * Create a new event instance.
     */
    public function __construct(Event $model, User $user)
    {
        $this->user = $user;
        $this->model = $model;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
//    public function broadcastOn(): array
//    {
//        return [
//            new PrivateChannel('channel-name'),
//        ];
//    }
}
