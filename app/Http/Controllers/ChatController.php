<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        $users = UserResource::collection($users)->resolve();

        return inertia('Chat/Index', compact('users'));
    }
}
