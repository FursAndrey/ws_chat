<?php

namespace App\Jobs;

use App\Events\SendUnreadableMessagesCountEvent;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreMessageStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private Message $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, Message $message)
    {
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data['user_ids'] as $key => $user_id) {
            MessageStatus::create([
                'user_id' => $user_id,
                'chat_id' => $this->data['chat_id'],
                'message_id' => $this->message->id,
            ]);

            $countMessages = MessageStatus::where('chat_id', '=', $this->data['chat_id'])
                ->where('user_id', '=', $user_id)
                ->where('is_read', false)
                ->count();
            broadcast(new SendUnreadableMessagesCountEvent($countMessages, $this->data['chat_id'], $user_id, $this->message))->toOthers();
        }
    }
}
