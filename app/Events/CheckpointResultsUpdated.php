<?php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class CheckpointResultsUpdated implements ShouldBroadcast {
    use Dispatchable, SerializesModels;
    public function __construct(public int $eventId, public array $state) {}
    public function broadcastOn(): Channel { return new Channel('checkpoint.event.'.$this->eventId); }
    public function broadcastAs(): string { return 'checkpoint.results.updated'; }
    public function broadcastWith(): array { return $this->state; }
}
