<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $channel_id, $sender_id;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel_id, ChatMessage $message)
    {
        $this->channel_id = $channel_id;
        $this->sender_id = $message->sender->id;
        $media = $message->getFirstMedia('media');
        $media = $media ? [
            'mime_type' => $media->mime_type,
            'url' => $media->original_url,
        ] : null;

        $this->data = [
            'id' => $message->id,
            'content' => $message->content,
            'created_at' => $message->created_at,
            'file' => $media,
            'sender' => [
                'id' => $message->sender->id,
                'name' => $message->sender->name,
                'profile_img' => $message->sender->getFirstMedia('profile_image')->original_url ?? null,
            ],
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.Channel.' . $this->channel_id);
    }
}
