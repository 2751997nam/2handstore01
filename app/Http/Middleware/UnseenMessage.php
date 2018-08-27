<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Repositories\MessageRepository;

class UnseenMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = Auth::check() ? Auth::user()->id : '';
        $message = new MessageRepository();
        $unseenMessage = $message->unseenMessage($id);
        $conversation = $message->clientConversation($id);
        if (!$request->is('admin/*')) {
            View::share('conversation', $conversation);
        }
        View::share('unseenMessage', $unseenMessage);
        
        return $next($request);
    }
}
