<?php

namespace App\Http\Controllers;

use App\Events\SendUnreadableMessagesCountEvent;
use App\Events\StoreMessageEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\MessageResource;
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

        try {
            DB::beginTransaction();
            
            $message = Message::create([
                'user_id' => auth()->id(),
                'chat_id' => $data['chat_id'],
                'body' => $data['body'],
            ]);

            foreach ($data['user_ids'] as $key => $user_id) {
                MessageStatus::create([
                    'user_id' => $user_id,
                    'chat_id' => $data['chat_id'],
                    'message_id' => $message->id,
                ]);

                $countMessages = MessageStatus::where('chat_id', '=', $data['chat_id'])
                    ->where('user_id', '=', $user_id)
                    ->where('is_read', false)
                    ->count();
                broadcast(new SendUnreadableMessagesCountEvent($countMessages, $data['chat_id'], $user_id, $message))->toOthers();
            }

            broadcast(new StoreMessageEvent($message))->toOthers();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return MessageResource::make($message)->resolve();
    }
}
