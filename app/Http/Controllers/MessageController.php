<?php

namespace App\Http\Controllers;

use App\Events\SendUnreadableMessagesCountEvent;
use App\Events\StoreMessageEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\MessageResource;
use App\Jobs\StoreMessageStatusJob;
use App\Models\Message;
use App\Models\MessageStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $message = Message::create([
            'user_id' => auth()->id(),
            'chat_id' => $data['chat_id'],
            'body' => $data['body'],
        ]);

        StoreMessageStatusJob::dispatch($data, $message)->onQueue('message_status');

        broadcast(new StoreMessageEvent($message))->toOthers();

        return MessageResource::make($message)->resolve();
    }
}
