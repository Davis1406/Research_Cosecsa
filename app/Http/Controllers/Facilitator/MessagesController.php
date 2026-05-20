<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\TrainingMaterial;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        $inbox = Message::with(['sender', 'material'])
            ->where('receiver_id', auth()->id())
            ->latest()
            ->get();

        $sent = Message::with(['receiver', 'material'])
            ->where('sender_id', auth()->id())
            ->latest()
            ->get();

        $unreadCount = $inbox->whereNull('read_at')->count();

        return view('facilitator.messages.index', compact('inbox', 'sent', 'unreadCount'));
    }

    public function compose(Request $request)
    {
        $recipients = User::whereHas('roles', fn($q) => $q->whereIn('title', ['Lead Facilitator', 'Facilitator', 'Trainee']))
            ->orderBy('name')
            ->get();

        $materials = TrainingMaterial::orderBy('title')->get();
        $toUser = $request->query('to') ? User::find($request->query('to')) : null;

        return view('facilitator.messages.compose', compact('recipients', 'materials', 'toUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string',
            'material_id' => 'nullable|exists:training_materials,id',
        ]);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject'     => $request->subject,
            'body'        => $request->body,
            'material_id' => $request->material_id ?: null,
        ]);

        return redirect()->route('facilitator.messages.index')->with('message', 'Message sent successfully.');
    }

    public function show(Message $message)
    {
        // Mark as read if recipient
        if ($message->receiver_id === auth()->id() && is_null($message->read_at)) {
            $message->update(['read_at' => now()]);
        }

        $message->load(['sender', 'receiver', 'material']);
        return view('facilitator.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        if (auth()->id() !== $message->sender_id && auth()->id() !== $message->receiver_id) {
            return back()->with('error', 'You cannot delete this message.');
        }
        $message->delete();
        return redirect()->route('facilitator.messages.index')->with('message', 'Message deleted.');
    }
}
