<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Trainee;
use App\TraineeDocument;
use App\TraineeDocumentComment;
use Illuminate\Http\Request;

class PresentationsController extends Controller
{
    /**
     * List all trainee presentations grouped by trainee.
     */
    public function index()
    {
        $trainees = Trainee::with([
            'documents' => fn($q) => $q->where('document_type', 'Presentation')->with('comments.user')->latest(),
        ])->orderBy('name')->get()->filter(fn($t) => $t->documents->isNotEmpty());

        return view('facilitator.presentations.index', compact('trainees'));
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
