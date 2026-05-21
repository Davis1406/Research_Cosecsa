<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Discussion;
use App\DiscussionReply;
use App\Schedule;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function index()
    {
        $discussions = Discussion::with(['user', 'schedule', 'latestReply.user'])
            ->withCount('replies')
            ->latest()
            ->get();

        $sessions = Schedule::discussable()->orderBy('day_number')->orderBy('start_time')->get();

        return view('facilitator.discussions.index', compact('discussions', 'sessions'));
    }

    public function create()
    {
        $sessions = Schedule::discussable()->orderBy('day_number')->orderBy('start_time')->get();
        return view('facilitator.discussions.form', compact('sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        $discussion = Discussion::create([
            'title'       => $request->title,
            'body'        => $request->body,
            'user_id'     => auth()->id(),
            'schedule_id' => $request->schedule_id ?: null,
            'is_general'  => $request->has('is_general') ? 1 : 0,
        ]);

        return redirect()->route('facilitator.discussions.show', $discussion)->with('message', 'Discussion started.');
    }

    public function show(Discussion $discussion)
    {
        $discussion->load(['user', 'schedule', 'replies.user']);
        return view('facilitator.discussions.show', compact('discussion'));
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
        $isLead = auth()->user()->roles->pluck('title')->contains('Lead Facilitator');
        if (auth()->id() !== $discussion->user_id && !$isLead) {
            return back()->with('error', 'You cannot delete this discussion.');
        }

        $discussion->delete();
        return redirect()->route('facilitator.discussions.index')->with('message', 'Discussion deleted.');
    }
}
