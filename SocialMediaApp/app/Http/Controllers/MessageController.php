<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get unique conversations
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first();
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $currentUser = auth()->user();

        // Get messages between current user and selected user
        $messages = Message::where(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $currentUser->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $receiver)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'content' => $validated['content'],
        ]);

        // Broadcast message via Reverb
        broadcast(new MessageSent($message))->toOthers();

        if (request()->wantsJson()) {
            return response()->json(['message' => $message->load(['sender', 'receiver'])]);
        }

        return redirect()->back();
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('view', $message);

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
