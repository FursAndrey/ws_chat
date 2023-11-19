<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PrivateChatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedChats = Auth::user()->chats->pluck('id')->toArray();
        $chatIdFromRoute = $request->route()->parameters()['chat']->id;
        
        if (!in_array($chatIdFromRoute, $allowedChats)) {
            return redirect()->route('chats.index');
        }

        return $next($request);
    }
}
