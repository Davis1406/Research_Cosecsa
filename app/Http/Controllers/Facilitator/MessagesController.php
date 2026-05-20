<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\TrainingMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    /**
     * Show conversation list (inbox grouped by user).
     */
    public function index()
    {
        $me = auth()->id();

        // Get all unique user pairs and the latest message per conversation
        $conversations = Message::with(['sender', 'receiver'])
            ->where(function ($q) use ($me) {
                $q->where('sender_id', $me)->orWhere('receiver_id', $me);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($msg) use ($me) {
                // Key = the OTHER user's id
                return $msg->sender_id === $me ? $msg->receiver_id : $msg->sender_id;
            })
            ->map(function ($msgs) use ($me) {
                $latest  = $msgs->first(); // already ordered desc
                $other   = $latest->sender_id === $me ? $latest->receiver : $latest->sender;
                $unread  = $msgs->filter(fn($m) => $m->receiver_id === $me && is_null($m->read_at))->count();
                return compact('latest', 'other', 'unread');
            });

        // All facilitators for "New chat" button
        $users = User::whereHas('roles', fn($q) => $q->whereIn('title', ['Lead Facilitator', 'Facilitator', 'Trainee']))
            ->where('id', '!=', $me)
            ->orderBy('name')
            ->get();

        $materials = TrainingMaterial::orderBy('title')->get();

        return view('facilitator.messages.index', compact('conversations', 'users', 'materials'));
    }

    /**
     * Open conversation thread with a specific user.
     */
    public function thread(User $user)
    {
        $me = auth()->id();

        // Mark all their messages to me as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::with(['material'])
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $materials = TrainingMaterial::orderBy('title')->get();

        return view('facilitator.messages.thread', compact('user', 'messages', 'materials'));
    }

    /**
     * Send a message in a thread (AJAX + fallback).
     */
    public function send(Request $request, User $user)
    {
        $request->validate([
            'body'        => 'required|string|max:5000',
            'material_id' => 'nullable|exists:training_materials,id',
            'attachment'  => 'nullable|file|max:51200', // 50 MB
        ]);

        $attPath = null; $attName = null; $attMime = null;
        if ($request->hasFile('attachment')) {
            $file    = $request->file('attachment');
            $attPath = $file->store('chat/attachments', 'public');
            $attName = $file->getClientOriginalName();
            $attMime = $file->getMimeType();
        }

        $message = Message::create([
            'sender_id'       => auth()->id(),
            'receiver_id'     => $user->id,
            'subject'         => 'chat',
            'body'            => $request->body,
            'material_id'     => $request->material_id ?: null,
            'attachment_path' => $attPath,
            'attachment_name' => $attName,
            'attachment_mime' => $attMime,
        ]);

        $message->load('material');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id'              => $message->id,
                'body'            => $message->body,
                'created_at'      => $message->created_at->format('g:i A'),
                'material_title'  => $message->material?->title,
                'material_id'     => $message->material_id,
                'attachment_name' => $attName,
                'attachment_path' => $attPath ? asset('storage/' . $attPath) : null,
                'attachment_mime' => $attMime,
            ]);
        }

        return redirect()->route('facilitator.messages.thread', $user);
    }

    /**
     * Poll for new messages since a given ID (for real-time feel).
     */
    public function poll(Request $request, User $user)
    {
        $me      = auth()->id();
        $sinceId = (int) $request->query('since', 0);

        $messages = Message::with('material')
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me);
            })
            ->where('id', '>', $sinceId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'              => $m->id,
                'body'            => $m->body,
                'created_at'      => $m->created_at->format('g:i A'),
                'material_title'  => $m->material?->title,
                'material_id'     => $m->material_id,
                'attachment_name' => $m->attachment_name,
                'attachment_path' => $m->attachment_path ? asset('storage/' . $m->attachment_path) : null,
                'attachment_mime' => $m->attachment_mime,
            ]);

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        if (auth()->id() !== $message->sender_id && auth()->id() !== $message->receiver_id) {
            return back()->with('error', 'You cannot delete this message.');
        }
        $otherId = $message->sender_id === auth()->id() ? $message->receiver_id : $message->sender_id;
        $message->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('facilitator.messages.index')->with('message', 'Message deleted.');
    }

    // ── Legacy compat kept for admin ──────────────────────────────────
    public function compose(Request $request)
    {
        $users = User::whereHas('roles', fn($q) => $q->whereIn('title', ['Lead Facilitator', 'Facilitator', 'Trainee']))
            ->where('id', '!=', auth()->id())
            ->orderBy('name')->get();
        $toUser = $request->query('to') ? User::find($request->query('to')) : null;

        if ($toUser) {
            return redirect()->route('facilitator.messages.thread', $toUser);
        }

        return view('facilitator.messages.index', [
            'conversations' => collect(),
            'users'         => $users,
            'materials'     => TrainingMaterial::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->receiver_id);
        return $this->send($request, $user);
    }

    public function show(Message $message)
    {
        $other = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
        return redirect()->route('facilitator.messages.thread', $other);
    }
}
