<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeeklyRankingNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user_id;
    public $body, $id, $type;

    public function __construct($user_id, $body, $type, $id)
    {
        $this->user_id = $user_id;
        $this->body = $body;
        $this->type = $type;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->user_id);
    }
}
