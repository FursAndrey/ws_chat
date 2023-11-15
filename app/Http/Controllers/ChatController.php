<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $users = UserResource::collection($users)->resolve();

        $chats = auth()->user()->chats()->has('messages')->get();
        $chats = ChatResource::collection($chats)->resolve();

        return inertia('Chat/Index', compact('users', 'chats'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $data['users'][] = auth()->id();
        sort($data['users']);
        $userIds = implode('-', $data['users']);
        
        try {
            DB::beginTransaction();
            
            $chat = Chat::firstOrCreate(
                [
                    'users' => $userIds
                ],
                [
                    'users' => $userIds,
                    'title' => $data['title'],
                ]
            );

            $chat->users()->sync($data['users']);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('chats.show', $chat->id);
    }

    public function show(Chat $chat)
    {
        $users = $chat->users()->get();
        $users = UserResource::collection($users)->resolve();
        $messages = $chat->messages()->with('user')->orderBy('id', 'DESC')->get();
        $messages = MessageResource::collection($messages)->resolve();

        $chat = ChatResource::make($chat)->resolve();

        return inertia('Chat/Show', compact('chat', 'users', 'messages'));
    }
}
