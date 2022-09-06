<?php

namespace App\Jobs;

use App\Events\NewNotification;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notifiable;
    protected $user_id;
    protected $body;
    protected $sender_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable_id, $notifiable_type, $user_id, $body, $sender_id)
    {
        if ($notifiable_type == 'channel') {
            $this->notifiable = Channel::find($notifiable_id);
        }

        $this->user_id = $user_id;
        $this->body = $body;
        $this->sender_id = $sender_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->notifiable->notifications()->create([
                'user_id' => $this->user_id,
                'body' => $this->body,
                'sender_id' => $this->sender_id
            ]);
        } catch (\Exception $e) {
            Log::error("Job Errors:" . print_r($e->getMessage(), 1));
        }
    }
}
