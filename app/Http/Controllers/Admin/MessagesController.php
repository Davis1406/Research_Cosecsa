<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\TrainingMaterial;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        $messages = Message::with(['sender', 'receiver', 'material'])
            ->latest()
            ->get();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        $message->load(['sender', 'receiver', 'material']);
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('message', 'Message deleted.');
    }
}
