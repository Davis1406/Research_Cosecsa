<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TraineeDocument;
use App\TraineeDocumentComment;
use Illuminate\Http\Request;

class PresentationsController extends Controller
{
    public function view(TraineeDocument $document)
    {
        $document->load(['trainee', 'comments.user', 'reviewers']);
        return view('admin.presentations.view', compact('document'));
    }

    public function comment(Request $request, TraineeDocument $document)
    {
        $request->validate(['comment' => 'required|string|max:2000']);

        TraineeDocumentComment::create([
            'trainee_document_id' => $document->id,
            'user_id'             => auth()->id(),
            'comment'             => $request->comment,
        ]);

        return back()->with('message', 'Comment posted.');
    }
}
