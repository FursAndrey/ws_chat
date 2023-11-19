<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStatus\MessageStatusRequest;
use App\Models\MessageStatus;
use Illuminate\Http\Request;

class MessageStatusController extends Controller
{
    public function update(MessageStatusRequest $request)
    {
        $data = $request->validated();
        
        $countMessages = MessageStatus::where('message_id', '=', $data['message_id'])
            ->where('user_id', '=', $data['user_id'])
            ->update(['is_read' => true]);
    }
}
