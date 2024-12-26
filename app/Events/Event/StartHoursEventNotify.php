<?php

namespace App\Events\Event;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartHoursEventNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $user;
    /**
     * Create a new event instance.
     */
    public function __construct($model, $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    public function broadcastWith(): array
    {
        return [
            'message'    => 'Событие начнётся в течении суток',
            'content'   => $this->model
        ];
    }

//    public function broadcastAs(): string
//    {
//        return 'order.created';
//    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('App.Models.User.' . $this->user->id);
    }
}
