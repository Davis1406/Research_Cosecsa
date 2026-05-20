<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\TrainingMaterial;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Show conversations the logged-in admin user is part of.
     * Each admin account only sees their own chats — not everyone else's.
     */
    public function index()
    {
        $me = auth()->id();

        $conversations = Message::with(['sender', 'receiver'])
            ->where(function ($q) use ($me) {
                $q->where('sender_id', $me)->orWhere('receiver_id', $me);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($msg) use ($me) {
                return (int)$msg->sender_id === $me
                    ? (int)$msg->receiver_id
                    : (int)$msg->sender_id;
            })
            ->map(function ($msgs) use ($me) {
                $latest = $msgs->first();
                $other  = (int)$latest->sender_id === $me ? $latest->receiver : $latest->sender;
                $unread = $msgs->filter(fn($m) => (int)$m->receiver_id === $me && is_null($m->read_at))->count();
                return compact('latest', 'other', 'unread');
            });

        $users = User::where('id', '!=', $me)->orderBy('name')->get();
        $materials = TrainingMaterial::orderBy('title')->get();

        return view('admin.messages.index', compact('conversations', 'users', 'materials'));
    }

    public function thread(User $user)
    {
        $me = auth()->id();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::with(['material'])
            ->where(function ($q) use ($me, $user) {
                $q->where(function ($inner) use ($me, $user) {
                    $inner->where('sender_id', $me)->where('receiver_id', $user->id);
                })->orWhere(function ($inner) use ($me, $user) {
                    $inner->where('sender_id', $user->id)->where('receiver_id', $me);
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $materials = TrainingMaterial::orderBy('title')->get();

        return view('admin.messages.thread', compact('user', 'messages', 'materials'));
    }

    public function send(Request $request, User $user)
    {
        $request->validate([
            'body'        => 'required|string|max:5000',
            'material_id' => 'nullable|exists:training_materials,id',
            'attachment'  => 'nullable|file|max:51200',
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

        return redirect()->route('admin.messages.thread', $user);
    }

    public function poll(Request $request, User $user)
    {
        $me      = auth()->id();
        $sinceId = (int) $request->query('since', 0);

        $messages = Message::with('material')
            ->where('sender_id', $user->id)
            ->where('receiver_id', $me)
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

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function show(Message $message)
    {
        $other = (int)$message->sender_id === auth()->id() ? $message->receiver : $message->sender;
        return redirect()->route('admin.messages.thread', $other);
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('message', 'Message deleted.');
    }
}
