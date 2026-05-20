<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Discussion;
use App\DiscussionReply;
use App\Schedule;
use Illuminate\Http\Request;

class DiscussionsController extends Controller
{
    public function index()
    {
        $discussions = Discussion::with(['user', 'schedule', 'latestReply.user'])
            ->withCount('replies')
            ->latest()
            ->get();

        $sessions = Schedule::orderBy('day_number')->orderBy('start_time')->get();

        return view('admin.discussions.index', compact('discussions', 'sessions'));
    }

    public function show(Discussion $discussion)
    {
        $discussion->load(['user', 'schedule', 'replies.user']);
        return view('admin.discussions.show', compact('discussion'));
    }

    public function reply(Request $request, Discussion $discussion)
    {
        $request->validate(['body' => 'required|string']);

        DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id'       => auth()->id(),
            'body'          => $request->body,
        ]);

        return back()->with('message', 'Reply posted.');
    }

    public function destroy(Discussion $discussion)
    {
        $discussion->delete();
        return redirect()->route('admin.discussions.index')->with('message', 'Discussion deleted.');
    }
}
