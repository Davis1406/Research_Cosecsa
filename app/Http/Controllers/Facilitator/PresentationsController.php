<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Trainee;
use App\TraineeDocument;
use App\TraineeDocumentComment;
use App\User;
use App\Role;
use Illuminate\Http\Request;

class PresentationsController extends Controller
{
    /**
     * List all trainee presentations grouped by trainee.
     */
    public function index()
    {
        $user   = auth()->user();
        $isLead = $user->roles->pluck('title')->contains('Lead Facilitator');

        $trainees = Trainee::with([
            'documents' => fn($q) => $q->where('document_type', 'Presentation')
                ->with('comments.user', 'reviewers')->latest(),
        ])->orderBy('name')->get()->filter(fn($t) => $t->documents->isNotEmpty());

        // Facilitators who can be assigned as reviewers
        $facilitatorUsers = $isLead
            ? User::whereHas('roles', fn($q) => $q->whereIn('title', ['Facilitator', 'Lead Facilitator']))
                  ->orderBy('name')->get()
            : collect();

        return view('facilitator.presentations.index', compact('trainees', 'isLead', 'facilitatorUsers'));
    }

    /**
     * Full-page viewer for a single trainee presentation + comment thread.
     */
    public function view(TraineeDocument $document)
    {
        abort_if($document->document_type !== 'Presentation', 404);
        $document->load('trainee', 'comments.user');
        return view('facilitator.presentations.view', compact('document'));
    }

    /**
     * Assign 1–2 reviewers to a presentation (Lead Facilitator only).
     */
    public function assignReviewers(Request $request, TraineeDocument $document)
    {
        abort_if(!auth()->user()->roles->pluck('title')->contains('Lead Facilitator'), 403);
        abort_if($document->document_type !== 'Presentation', 404);

        $request->validate([
            'reviewer_ids'   => 'nullable|array|max:4',
            'reviewer_ids.*' => 'exists:users,id',
        ]);

        $document->reviewers()->sync($request->input('reviewer_ids', []));

        return back()->with('message', 'Reviewers assigned successfully.');
    }

    /**
     * Store a facilitator comment on a presentation.
     */
    public function comment(Request $request, TraineeDocument $document)
    {
        abort_if($document->document_type !== 'Presentation', 404);

        $request->validate(['comment' => 'required|string|max:2000']);

        TraineeDocumentComment::create([
            'trainee_document_id' => $document->id,
            'user_id'             => auth()->id(),
            'comment'             => $request->comment,
        ]);

        return back()->with('message', 'Comment posted successfully.');
    }
}
